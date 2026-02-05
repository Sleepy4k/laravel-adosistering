@extends('layouts.dashboard')

@section('title', 'Statistik')

@push('styles')
<style>
    /* Chart container styling */
    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
</style>
@endpush

@section('content')
    {{-- Store chart module path for Alpine.js --}}
    <script>
        window.__CHART_LOADER_PATH__ = "{{ Vite::asset('resources/js/chart-loader.js') }}";
        window.__FIREBASE_MODULE_PATH__ = "{{ Vite::asset('resources/js/firebase.js') }}";
        window.__FIREBASE_CONFIG__ = @json(config('firebase'));
        window.__CURRENT_PERIOD__ = "{{ $currentPeriod }}";
        
        // Define statistikPage synchronously (not as module) so it's available immediately
        window.statistikPage = function() {
            return {
                // State
                chartsLoaded: false,
                chartsInitializing: false, // Prevent multiple initialization
                isLoading: true,
                currentPeriod: window.__CURRENT_PERIOD__ || 'today',
                
                // Smoothing feature state
                useSmoothing: false,
                isSmoothingLoading: false,
                smoothedCache: {},
                rawLogs: {}, // Store raw numeric logs per block for smoothing (from MySQL backend - not ready yet)
                noNumericHistory: false, // Flag if no numeric history available (set to false to enable smoothing button)
                
                // Firebase data
                firebaseBlocks: [],
                historyData: [], // Store history data for frequency calculation
                chartInstances: {},
                
                // Summary data
                summary: {
                    total_penggunaan_air: 0,
                    kelembaban_rata_rata: 0
                },
                
                // Processed blocks data
                bloks: [],
                
                async init() {
                    // Wait for DOM to be ready
                    await this.$nextTick();
                    
                    // Initialize Firebase and load data (history will be loaded after Firebase is ready)
                    await this.initializeFirebaseData();
                },
                
                async initializeFirebaseData() {
                    try {
                        const firebaseModulePath = window.__FIREBASE_MODULE_PATH__;
                        const config = window.__FIREBASE_CONFIG__;
                        
                        if (!config || !config.api_key) {
                            console.error('Firebase config not found');
                            this.isLoading = false;
                            return;
                        }
                        
                        // Import the module - this executes the module code
                        // which registers FirebaseModule on window
                        await import(firebaseModulePath);
                        
                        // After import, module should be available on window
                        // because firebase.js sets window.FirebaseModule
                        const firebaseModule = window.FirebaseModule;
                        
                        if (!firebaseModule || typeof firebaseModule.initializeFirebase !== 'function') {
                            console.error('Firebase module not found on window.FirebaseModule');
                            this.isLoading = false;
                            return;
                        }
                        
                        // Initialize Firebase with config from Laravel
                        const firebaseConfig = {
                            apiKey: config.api_key,
                            authDomain: config.auth_domain,
                            databaseURL: config.database_url,
                            projectId: config.project_id,
                            storageBucket: config.storage_bucket,
                            messagingSenderId: config.messaging_sender_id,
                            appId: config.app_id
                        };
                        
                        const db = firebaseModule.initializeFirebase(firebaseConfig);
                        
                        if (!db) {
                            console.error('Firebase database initialization failed');
                            this.isLoading = false;
                            return;
                        }
                        
                        // Listen to MAOS data
                        firebaseModule.listenToMAOS((result) => {
                            if (result.blocks && result.blocks.length > 0) {
                                this.firebaseBlocks = result.blocks;
                                
                                // Load history data after blocks are loaded
                                this.loadHistoryData().then(() => {
                                    this.processStatisticsData();
                                });
                            }
                            this.isLoading = false;
                        });
                        
                    } catch (error) {
                        console.error('Failed to initialize Firebase:', error);
                        this.isLoading = false;
                    }
                },
                
                async loadHistoryData() {
                    try {
                        // Get Firebase module from window
                        const firebaseModule = window.FirebaseModule;
                        
                        if (!firebaseModule) {
                            console.error('Firebase module not available for history loading');
                            return;
                        }
                        
                        // Get history data from MAOS path
                        const maosData = await firebaseModule.getData('MAOS');
                        
                        if (!maosData) {
                            return;
                        }
                        
                        const historyEntries = [];
                        
                        // Loop through blocks: MAOS -> Block_A, Block_B, etc
                        Object.keys(maosData).forEach(blockName => {
                            const blockData = maosData[blockName];
                            if (!blockData || typeof blockData !== 'object') return;
                            
                            // Loop through sprayers: Block_A -> Sprayer_1, Sprayer_2, etc
                            Object.keys(blockData).forEach(sprayerName => {
                                // Skip non-sprayer keys
                                if (!sprayerName.includes('Sprayer')) return;
                                
                                const sprayerData = blockData[sprayerName];
                                if (!sprayerData) return;
                                
                                // ==========================================
                                // ONLY get history data for irrigation frequency calculation
                                // Chart data comes from realtime (firebaseBlocks)
                                // ==========================================
                                if (sprayerData.history && typeof sprayerData.history === 'object') {
                                    // Loop through history dates
                                    Object.keys(sprayerData.history).forEach(dateKey => {
                                        const dateData = sprayerData.history[dateKey];
                                        if (!dateData || typeof dateData !== 'object') return;
                                        
                                        const uniqueIds = Object.keys(dateData);
                                        
                                        uniqueIds.forEach(uniqueId => {
                                            const entry = dateData[uniqueId];
                                            if (!entry || typeof entry !== 'object') return;
                                            
                                            const timestamp = entry.timestamp || dateKey;
                                            
                                            // Each unique ID = 1 irrigation event (for frequency count)
                                            historyEntries.push({
                                                blockName: blockName,
                                                sprayerName: sprayerName,
                                                date: dateKey,
                                                uniqueId: uniqueId,
                                                timestamp: timestamp,
                                                jenisIrigasi: entry.jenisIrigasi || 'Otomatis'
                                            });
                                        });
                                    });
                                }
                            });
                        });
                        
                        this.historyData = historyEntries;// Reprocess statistics with history data (for frequency calculation)
                        if (this.firebaseBlocks.length > 0) {
                            this.processStatisticsData();
                        }
                        
                    } catch (error) {
                        console.error('Failed to load history data:', error);
                    }
                },
                
                processStatisticsData() {
                    // Process blocks data for statistics
                    const processedBloks = [];
                    let totalAirKeseluruhan = 0;
                    let totalKelembabanKeseluruhan = 0;
                    let totalSprayerCount = 0;
                    
                    this.firebaseBlocks.forEach((block, index) => {
                        // Calculate block statistics
                        const sprayerCount = block.sprayers.length || 1;
                        
                        // Kelembaban data
                        const moistureValues = block.sprayers.map(s => s.moisture);
                        const avgMoisture = block.avgMoisture || 0;
                        const minMoisture = moistureValues.length > 0 ? Math.min(...moistureValues) : 0;
                        const maxMoisture = moistureValues.length > 0 ? Math.max(...moistureValues) : 0;
                        
                        // Determine moisture status
                        let moistureStatus = 'Lembab';
                        if (avgMoisture < 30) {
                            moistureStatus = 'Kering';
                        } else if (avgMoisture > 70) {
                            moistureStatus = 'Basah';
                        }
                        
                        // Total air keluar (sum of totalVolume from all sprayers)
                        const totalAirKeluar = block.totalVolume || 0;
                        
                        // Debit air rata-rata (average flow rate)
                        const debitAirRataRata = block.avgFlowRate || 0;
                        
                        // Calculate irrigation frequency from history data
                        const frekuensiIrigasi = this.calculateIrrigationFrequency(block.name);
                        
                        // Generate chart data based on period
                        const chartData = this.generateChartData(block);
                        
                        const blokData = {
                            id: index + 1,
                            nama: block.name,
                            frekuensi_irigasi: frekuensiIrigasi,
                            kelembaban: {
                                rata_rata: avgMoisture,
                                min: minMoisture,
                                max: maxMoisture,
                                status: moistureStatus
                            },
                            kelembaban_rata_rata: avgMoisture,
                            total_air_digunakan: totalAirKeluar,
                            debit_air_rata_rata: debitAirRataRata,
                            chart_kelembaban: chartData.kelembaban,
                            chart_penggunaan_air: chartData.penggunaanAir,
                            sprayers: block.sprayers
                        };
                        
                        processedBloks.push(blokData);
                        
                        // Accumulate for overall summary
                        totalAirKeseluruhan += totalAirKeluar;
                        totalKelembabanKeseluruhan += avgMoisture;
                        totalSprayerCount++;
                    });
                    
                    // Update bloks
                    this.bloks = processedBloks;
                    
                    // Update summary
                    this.summary = {
                        total_penggunaan_air: totalAirKeseluruhan,
                        kelembaban_rata_rata: totalSprayerCount > 0 
                            ? totalKelembabanKeseluruhan / totalSprayerCount 
                            : 0
                    };
                    
                    // Initialize charts after data is ready
                    this.$nextTick(() => {
                        if (!this.chartsInitializing) {
                            this.initializeCharts();
                        }
                    });
                },
                
                calculateIrrigationFrequency(blockName) {
                    // Filter history data for this block and current period
                    const now = new Date();
                    let startDate;
                    
                    if (this.currentPeriod === 'today') {
                        // Last 24 hours (not just calendar day)
                        startDate = new Date(now);
                        startDate.setHours(now.getHours() - 24);
                    } else if (this.currentPeriod === '7days') {
                        // Last 7 days
                        startDate = new Date(now);
                        startDate.setDate(startDate.getDate() - 7);
                    } else if (this.currentPeriod === '30days') {
                        // Last 30 days
                        startDate = new Date(now);
                        startDate.setDate(startDate.getDate() - 30);
                    }
                    
                    // Filter history entries for this block within the period
                    const blockHistory = this.historyData.filter(entry => {
                        if (entry.blockName !== blockName) return false;
                        
                        // Parse timestamp to Date object
                        const entryDate = this.parseHistoryTimestamp(entry.timestamp);
                        if (!entryDate) return false;
                        
                        return entryDate >= startDate && entryDate <= now;
                    });
                    
                    // Count by irrigation type (currently all automatic)
                    const otomatis = blockHistory.filter(entry => entry.jenisIrigasi === 'Otomatis').length;
                    const manual = blockHistory.filter(entry => entry.jenisIrigasi === 'Manual').length;
                    
                    return {
                        otomatis: otomatis,
                        manual: manual,
                        total: blockHistory.length
                    };
                },
                
                parseHistoryTimestamp(timestampStr) {
                    if (!timestampStr) return null;
                    
                    // Format: "DD-MM-YYYY HH:MM:SS" or just "DD-MM-YYYY"
                    const parts = timestampStr.split(' ');
                    const dateParts = parts[0].split('-');
                    
                    if (dateParts.length !== 3) return null;
                    
                    const day = parseInt(dateParts[0]);
                    const month = parseInt(dateParts[1]) - 1; // JavaScript months are 0-indexed
                    const year = parseInt(dateParts[2]);
                    
                    if (parts.length > 1) {
                        // Has time component
                        const timeParts = parts[1].split(':');
                        const hour = parseInt(timeParts[0]) || 0;
                        const minute = parseInt(timeParts[1]) || 0;
                        const second = parseInt(timeParts[2]) || 0;
                        
                        return new Date(year, month, day, hour, minute, second);
                    } else {
                        // Date only
                        return new Date(year, month, day);
                    }
                },
                
                generateChartData(block) {
                    const now = new Date();
                    const currentMoisture = block.avgMoisture || 0;
                    const currentVolume = block.totalVolume || 0;
                    const hasRealData = currentMoisture > 0 || currentVolume > 0;

                    // Check cache first
                    const cacheKey = this.getCacheKey(block.name, this.useSmoothing);
                    if (this.smoothedCache[cacheKey]) {
                        return this.smoothedCache[cacheKey];
                    }

                    // If smoothing is enabled and we have raw logs from MySQL backend
                    if (this.useSmoothing && this.rawLogs[block.name] && this.rawLogs[block.name].length > 0) {
                        const aggregated = this.aggregateByPeriod(this.rawLogs[block.name], this.currentPeriod);

                        if (aggregated.labels.length > 0) {
                            const window = this.getSmoothingWindow();
                            const kelembabanData = this.smoothMovingAverage(aggregated.moistureValues, window);
                            const penggunaanAirData = this.smoothMovingAverage(aggregated.volumeValues, window);

                            const result = {
                                kelembaban: aggregated.labels.map((waktu, idx) => ({
                                    waktu: waktu,
                                    nilai: kelembabanData[idx]
                                })),
                                penggunaanAir: aggregated.labels.map((waktu, idx) => ({
                                    waktu: waktu,
                                    nilai: penggunaanAirData[idx]
                                }))
                            };

                            this.smoothedCache[cacheKey] = result;
                            return result;
                        }
                    }

                    // Generate chart data from realtime Firebase data
                    return this.generateRawChartData(block, now, currentMoisture, currentVolume, hasRealData);
                },
                
                /**
                 * Generate raw chart data from realtime Firebase data
                 * Chart should display only realtime data (not history)
                 */
                generateRawChartData(block, now, currentMoisture, currentVolume, hasRealData) {
                    let labels = [];
                    let kelembabanData = [];
                    let penggunaanAirData = [];
                    
                    if (this.currentPeriod === 'today') {
                        // Per jam untuk hari ini (24 jam)
                        const currentHour = now.getHours();
                        
                        // Create hourly labels
                        for (let i = 0; i <= currentHour; i += 2) {
                            const hour = i.toString().padStart(2, '0') + ':00';
                            labels.push(hour);
                            
                            // All hours show current realtime data (since we don't have historical data yet)
                            if (hasRealData) {
                                kelembabanData.push(currentMoisture);
                                penggunaanAirData.push(currentVolume);
                            } else {
                                kelembabanData.push(null);
                                penggunaanAirData.push(null);
                            }
                        }
                    } else if (this.currentPeriod === '7days') {
                        // Per hari untuk 7 hari terakhir
                        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                        for (let i = 6; i >= 0; i--) {
                            const date = new Date(now);
                            date.setDate(date.getDate() - i);
                            const dayName = days[date.getDay()];
                            labels.push(dayName);
                            
                            // Only today (i === 0) gets the realtime data
                            if (i === 0 && hasRealData) {
                                kelembabanData.push(currentMoisture);
                                penggunaanAirData.push(currentVolume);
                            } else {
                                // No data for past days
                                kelembabanData.push(null);
                                penggunaanAirData.push(null);
                            }
                        }
                    } else if (this.currentPeriod === '30days') {
                        // Per minggu untuk 30 hari terakhir (4 minggu)
                        for (let i = 3; i >= 0; i--) {
                            const weekLabel = 'Minggu ' + (4 - i);
                            labels.push(weekLabel);
                            
                            // Only current week (i === 0) gets the realtime data
                            if (i === 0 && hasRealData) {
                                kelembabanData.push(currentMoisture);
                                penggunaanAirData.push(currentVolume);
                            } else {
                                // No data for past weeks
                                kelembabanData.push(null);
                                penggunaanAirData.push(null);
                            }
                        }
                    }
                    
                    const cacheKey = this.getCacheKey(block.name, false);
                    const result = {
                        kelembaban: labels.map((waktu, idx) => ({
                            waktu: waktu,
                            nilai: kelembabanData[idx]
                        })),
                        penggunaanAir: labels.map((waktu, idx) => ({
                            waktu: waktu,
                            nilai: penggunaanAirData[idx]
                        }))
                    };
                    
                    // Cache the result
                    this.smoothedCache[cacheKey] = result;return result;
                },
                
                async initializeCharts() {
                    if (this.chartsInitializing || this.bloks.length === 0) {return;
                    }this.chartsInitializing = true;

                    try {
                        // Load Chart.js first if not loaded
                        const chartLoaderPath = window.__CHART_LOADER_PATH__;await import(chartLoaderPath);
                        
                        const chartModule = window.ChartModule;
                        
                        // Destroy existing charts using ChartModule (guaranteed to have Chart access)
                        if (chartModule && typeof chartModule.destroyAllCharts === 'function') {
                            chartModule.destroyAllCharts(this.bloks);
                        }

                        // Wait to ensure cleanup is complete
                        await new Promise(resolve => setTimeout(resolve, 100));

                        if (chartModule && typeof chartModule.initializeCharts === 'function') {chartModule.initializeCharts(this.bloks);
                            this.chartsLoaded = true;}
                    } catch (error) {
                        console.error('❌ Failed to load charts:', error);
                    } finally {
                        this.chartsInitializing = false;
                    }
                },
                
                destroyAllCharts() {try {
                        // Use ChartModule.destroyAllCharts if available (preferred method)
                        const chartModule = window.ChartModule;
                        if (chartModule && typeof chartModule.destroyAllCharts === 'function') {
                            chartModule.destroyAllCharts(this.bloks);
                            this.chartInstances = {};
                            this.chartsLoaded = false;
                            return;
                        }
                        
                        // Fallback: Check if Chart.js is loaded via window.Chart
                        if (typeof window.Chart === 'undefined') {this.chartInstances = {};
                            this.chartsLoaded = false;
                            return;
                        }
                        
                        // Method 1: Destroy by canvas ID using Chart.getChart
                        if (this.bloks && this.bloks.length > 0) {
                            this.bloks.forEach(blok => {
                                ['chartKelembaban' + blok.id, 'chartPenggunaanAir' + blok.id].forEach(canvasId => {
                                    const canvas = document.getElementById(canvasId);
                                    if (canvas) {
                                        try {
                                            const chart = window.Chart.getChart(canvas);
                                            if (chart) {chart.destroy();
                                            }
                                        } catch (e) {}
                                    }
                                });
                            });
                        }} catch (error) {
                        console.error('⚠️ Error destroying charts:', error);
                    }

                    this.chartInstances = {};
                    this.chartsLoaded = false;
                },
                
                // ========================================
                // SMOOTHING FUNCTIONS
                // ========================================
                
                /**
                 * Toggle smoothing mode and regenerate charts
                 */
                async toggleSmoothing() {
                    // If trying to enable smoothing but no numeric history, show alert and prevent
                    if (!this.useSmoothing && this.noNumericHistory) {
                        alert('Tidak ada data history numerik untuk mode Smoothing. Silakan gunakan mode Data Mentah.');
                        return;
                    }this.isSmoothingLoading = true;

                    try {
                        // Toggle the smoothing state
                        this.useSmoothing = !this.useSmoothing;

                        // IMPORTANT: Clear cache to force regenerationthis.smoothedCache = {};

                        // Reset chart states BEFORE destroying
                        this.chartsLoaded = false;
                        this.chartsInitializing = false;

                        // Wait a bit before reprocessing
                        await new Promise(resolve => setTimeout(resolve, 50));this.processStatisticsData();
                    } catch (error) {
                        console.error('Error toggling smoothing:', error);
                    } finally {
                        this.isSmoothingLoading = false;
                    }
                },
                
                /**
                 * Moving Average Smoothing
                 * @param {Array} arr - Array of numbers (can contain null)
                 * @param {Number} window - Window size for moving average
                 * @returns {Array} - Smoothed array
                 */
                smoothMovingAverage(arr, window = 5) {const out = new Array(arr.length).fill(null);
                    const sanitized = arr.map(v => (v === undefined || v === null || isNaN(v)) ? null : parseFloat(v));

                    for (let i = 0; i < sanitized.length; i++) {
                        const start = Math.max(0, i - window + 1);
                        const slice = sanitized.slice(start, i + 1).filter(v => v !== null);
                        if (slice.length === 0) continue;
                        out[i] = slice.reduce((a, b) => a + b, 0) / slice.length;
                    }return out;
                },
                
                /**
                 * Aggregate raw logs by period (today/7days/30days)
                 * @param {Array} rawLogs - Array of log entries with ts, moisture, flow, volume
                 * @param {String} period - 'today', '7days', '30days'
                 * @returns {Object} - { labels, moistureValues, volumeValues }
                 */
                aggregateByPeriod(rawLogs, period) {
                    const now = new Date();
                    const result = { labels: [], moistureValues: [], volumeValues: [] };if (!rawLogs || rawLogs.length === 0) {return result;
                    }
                    
                    // Filter logs by period
                    let startDate;
                    if (period === 'today') {
                        startDate = new Date(now);
                        startDate.setHours(0, 0, 0, 0);
                    } else if (period === '7days') {
                        startDate = new Date(now);
                        startDate.setDate(startDate.getDate() - 7);
                        startDate.setHours(0, 0, 0, 0);
                    } else if (period === '30days') {
                        startDate = new Date(now);
                        startDate.setDate(startDate.getDate() - 30);
                        startDate.setHours(0, 0, 0, 0);
                    }
                    
                    const filteredLogs = rawLogs.filter(log => log.ts >= startDate.getTime());if (period === 'today') {
                        // Aggregate per hour (0-23)
                        const hourlyBuckets = {};
                        for (let h = 0; h <= now.getHours(); h += 2) {
                            hourlyBuckets[h] = { moisture: [], volume: [] };
                        }
                        
                        filteredLogs.forEach(log => {
                            const logDate = new Date(log.ts);
                            const hour = Math.floor(logDate.getHours() / 2) * 2; // Round to even hours
                            if (hourlyBuckets[hour]) {
                                if (log.moisture !== null) hourlyBuckets[hour].moisture.push(log.moisture);
                                if (log.volume !== null) hourlyBuckets[hour].volume.push(log.volume);
                            }
                        });
                        
                        Object.keys(hourlyBuckets).sort((a, b) => a - b).forEach(hour => {
                            const bucket = hourlyBuckets[hour];
                            result.labels.push(hour.toString().padStart(2, '0') + ':00');
                            result.moistureValues.push(
                                bucket.moisture.length > 0 
                                    ? bucket.moisture.reduce((a, b) => a + b, 0) / bucket.moisture.length 
                                    : null
                            );
                            result.volumeValues.push(
                                bucket.volume.length > 0 
                                    ? bucket.volume.reduce((a, b) => a + b, 0) / bucket.volume.length 
                                    : null
                            );
                        });
                        
                    } else if (period === '7days') {
                        // Aggregate per day
                        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                        const dailyBuckets = {};
                        
                        for (let i = 6; i >= 0; i--) {
                            const date = new Date(now);
                            date.setDate(date.getDate() - i);
                            const dateKey = date.toISOString().split('T')[0];
                            dailyBuckets[dateKey] = { 
                                dayName: days[date.getDay()], 
                                moisture: [], 
                                volume: [] 
                            };
                        }
                        
                        filteredLogs.forEach(log => {
                            const logDate = new Date(log.ts);
                            const dateKey = logDate.toISOString().split('T')[0];
                            if (dailyBuckets[dateKey]) {
                                if (log.moisture !== null) dailyBuckets[dateKey].moisture.push(log.moisture);
                                if (log.volume !== null) dailyBuckets[dateKey].volume.push(log.volume);
                            }
                        });
                        
                        Object.keys(dailyBuckets).sort().forEach(dateKey => {
                            const bucket = dailyBuckets[dateKey];
                            result.labels.push(bucket.dayName);
                            result.moistureValues.push(
                                bucket.moisture.length > 0 
                                    ? bucket.moisture.reduce((a, b) => a + b, 0) / bucket.moisture.length 
                                    : null
                            );
                            result.volumeValues.push(
                                bucket.volume.length > 0 
                                    ? bucket.volume.reduce((a, b) => a + b, 0) / bucket.volume.length 
                                    : null
                            );
                        });
                        
                    } else if (period === '30days') {
                        // Aggregate per week (4 minggu untuk 30 hari)
                        const weeklyBuckets = {};
                        for (let w = 3; w >= 0; w--) {
                            weeklyBuckets[w] = { moisture: [], volume: [] };
                        }
                        
                        filteredLogs.forEach(log => {
                            const logDate = new Date(log.ts);
                            const daysDiff = Math.floor((now.getTime() - logDate.getTime()) / (1000 * 60 * 60 * 24));
                            const weekIndex = Math.min(3, Math.floor(daysDiff / 7));
                            if (weeklyBuckets[weekIndex]) {
                                if (log.moisture !== null) weeklyBuckets[weekIndex].moisture.push(log.moisture);
                                if (log.volume !== null) weeklyBuckets[weekIndex].volume.push(log.volume);
                            }
                        });
                        
                        // Reverse to show oldest first
                        [3, 2, 1, 0].forEach(w => {
                            const bucket = weeklyBuckets[w];
                            result.labels.push('Minggu ' + (4 - w));
                            result.moistureValues.push(
                                bucket.moisture.length > 0 
                                    ? bucket.moisture.reduce((a, b) => a + b, 0) / bucket.moisture.length 
                                    : null
                            );
                            result.volumeValues.push(
                                bucket.volume.length > 0 
                                    ? bucket.volume.reduce((a, b) => a + b, 0) / bucket.volume.length 
                                    : null
                            );
                        });
                    }
                    
                    return result;
                },
                
                /**
                 * Get cache key for smoothed data
                 */
                getCacheKey(blockName, isSmoothed) {
                    return `${blockName}::${this.currentPeriod}::${isSmoothed ? 'sm' : 'raw'}`;
                },
                
                /**
                 * Get smoothing window based on period
                 */
                getSmoothingWindow() {
                    switch (this.currentPeriod) {
                        case 'today':
                            return 5;
                        case '7days':
                            return 4;
                        case '30days':
                            return 3;
                        default:
                            return 5;
                    }
                },
                
                formatNumber(value, decimals = 2) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    }).format(value);
                },
                
                getMoistureStatusClass(status) {
                    switch(status) {
                        case 'Kering':
                            return 'bg-red-50 text-red-600 border-red-200';
                        case 'Basah':
                            return 'bg-blue-50 text-blue-600 border-blue-200';
                        default:
                            return 'bg-[#FFFBEB] text-[#C47E09] border-[#FDECCE]';
                    }
                }
            }
        }
    </script>

    <div class="max-w-7xl mx-auto" x-data="statistikPage()">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Statistik</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const now = new Date();
                    currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" loading="lazy" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>

        <!-- Period Filter Tabs -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Period Tabs -->
                <div class="flex items-center justify-center gap-6">
                    <a href="{{ route('user.statistik', ['period' => 'today']) }}" 
                       class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === 'today' ? 'text-[#67B744] underline underline-offset-8 decoration-2 decoration-[#67B744]' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('user.statistik', ['period' => '7days']) }}" 
                       class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === '7days' ? 'text-[#67B744] underline underline-offset-8 decoration-2 decoration-[#67B744]' : 'text-gray-500 hover:text-gray-700' }}">
                        7 Hari Terakhir
                    </a>
                    <a href="{{ route('user.statistik', ['period' => '30days']) }}" 
                       class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === '30days' ? 'text-[#67B744] underline underline-offset-8 decoration-2 decoration-[#67B744]' : 'text-gray-500 hover:text-gray-700' }}">
                        30 Hari Terakhir
                    </a>
                </div>
                
                <!-- Data Mode Toggle (Raw / Smoothed) -->
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Mode Data:</span>
                    
                    <!-- Toggle Switch Style Button -->
                    <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-xl">
                        <!-- Raw Button -->
                        <button 
                            type="button"
                            x-on:click="if(useSmoothing) { toggleSmoothing(); }"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer"
                            :class="!useSmoothing 
                                ? 'bg-white text-[#67B744] shadow-sm border border-[#67B744]' 
                                : 'bg-transparent text-gray-500 hover:text-gray-700'"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Data Mentah
                            </span>
                        </button>
                        
                        <!-- Smoothed Button -->
                        <button 
                            type="button"
                            x-on:click="if(!useSmoothing && !noNumericHistory) { toggleSmoothing(); }"
                            :disabled="noNumericHistory"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                            :class="[
                                noNumericHistory 
                                    ? 'bg-gray-200 text-gray-400 cursor-not-allowed' 
                                    : useSmoothing 
                                        ? 'bg-[#67B744] text-white shadow-sm cursor-pointer' 
                                        : 'bg-transparent text-gray-500 hover:text-gray-700 cursor-pointer'
                            ]"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                                Smoothing
                            </span>
                        </button>
                    </div>
                    
                    <!-- Loading indicator -->
                    <div x-show="isSmoothingLoading" class="flex items-center" style="display: none;">
                        <svg class="animate-spin h-5 w-5 text-[#67B744]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <!-- Warning if no numeric history (MySQL backend not ready) -->
                    <div x-show="noNumericHistory" 
                         class="flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-200 rounded-lg"
                         style="display: none;">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs text-blue-700">Realtime data only</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="isLoading" class="text-center py-12 bg-white rounded-2xl border border-gray-200 mb-6">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-primary mb-4"></div>
            <p class="text-gray-500 text-sm">Memuat data statistik dari Firebase...</p>
        </div>

        <!-- Summary Cards -->
        <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Total Penggunaan Air Keseluruhan -->
            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Total Penggunaan Air Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    <span x-text="formatNumber(summary.total_penggunaan_air)"></span>
                    <span class="text-lg font-normal">Liter</span>
                </p>
            </div>

            <!-- Kelembaban Rata Rata Keseluruhan -->
            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Kelembaban Rata Rata Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    <span x-text="formatNumber(summary.kelembaban_rata_rata)"></span>%
                </p>
            </div>
        </div>

        <!-- Blok Sections (Dynamic from Firebase) -->
        <template x-for="(blok, index) in bloks" :key="blok.id">
            <div class="bg-white rounded-2xl border border-[#C2C2C2] mb-6 overflow-hidden" x-data="{ expanded: index === 0 }">
                <!-- Blok Header (Accordion Toggle) -->
                <button 
                    @click="expanded = !expanded" 
                    class="w-full py-4 px-6 flex items-center justify-between hover:bg-gray-50 transition-colors"
                >
                    <h2 class="text-lg font-semibold text-[#4F4F4F]" x-text="blok.nama"></h2>
                    <svg 
                        class="w-5 h-5 text-gray-500 transition-transform duration-200" 
                        :class="{ 'rotate-180': expanded }"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>

                <!-- Blok Content -->
                <div x-show="expanded" x-collapse>
                    <div class="px-6 pb-6">
                        <!-- Statistik Cards -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <!-- Frekuensi Irigasi Aktif -->
                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Frekuensi Irigasi Aktif</p>
                                <p class="text-4xl font-bold text-[#467A30] mb-4" x-text="blok.frekuensi_irigasi.total"></p>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#BFDBFE] bg-[#EFF6FF] text-[#2563EB] text-xs font-medium">
                                        <span x-text="blok.frekuensi_irigasi.otomatis"></span>x Otomatis
                                    </span>
                                    <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#E5E5E5] bg-[#FAFAFA] text-[#525252] text-xs font-medium">
                                        <span x-text="blok.frekuensi_irigasi.manual"></span>x Manual
                                    </span>
                                </div>
                            </div>

                            <!-- Kelembaban Rata Rata -->
                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Kelembaban Rata Rata</p>
                                <div class="flex items-center gap-2 mb-4">
                                    <p class="text-4xl font-bold text-[#467A30]">
                                        <span x-text="formatNumber(blok.kelembaban.rata_rata)"></span>%
                                    </p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md border text-xs font-medium"
                                          :class="getMoistureStatusClass(blok.kelembaban.status)"
                                          x-text="blok.kelembaban.status">
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#FEE2E2] bg-[#FEF2F2] text-[#DC2626] text-xs font-medium">
                                        <span x-text="formatNumber(blok.kelembaban.min, 0)"></span>%
                                    </span>
                                    <span class="text-gray-400">-</span>
                                    <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#D3F3DF] bg-[#F2FDF5] text-[#16A34A] text-xs font-medium">
                                        <span x-text="formatNumber(blok.kelembaban.max, 0)"></span>%
                                    </span>
                                </div>
                            </div>

                            <!-- Total Air Keluar -->
                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Total Air Keluar</p>
                                <p class="text-4xl font-bold text-[#467A30] mb-4">
                                    <span x-text="formatNumber(blok.total_air_digunakan)"></span>
                                </p>
                                <p class="text-sm text-[#4F4F4F]">Liter</p>
                            </div>

                            <!-- Debit Air Rata Rata -->
                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Debit Air Rata Rata</p>
                                <p class="text-4xl font-bold text-[#467A30] mb-4">
                                    <span x-text="formatNumber(blok.debit_air_rata_rata)"></span>
                                </p>
                                <p class="text-sm text-[#4F4F4F]">Liter/menit</p>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- Kelembaban Tanah Chart -->
                            <div class="bg-white border border-[#C2C2C2] rounded-xl p-4">
                                <h3 class="text-base font-semibold text-[#4F4F4F] mb-4">Kelembaban Tanah</h3>
                                <div class="chart-container">
                                    <canvas :id="'chartKelembaban' + blok.id"></canvas>
                                </div>
                                <div class="flex items-center justify-center gap-2 mt-4">
                                    <span class="w-4 h-4 border-3 border-[#67B744] bg-white flex items-center justify-center">
                                    </span>
                                    <span class="text-xs text-gray-500">Kelembaban Tanah</span>
                                </div>
                            </div>

                            <!-- Penggunaan Air Chart -->
                            <div class="bg-white border border-[#C2C2C2] rounded-xl p-4">
                                <h3 class="text-base font-semibold text-[#4F4F4F] mb-4">Penggunaan Air</h3>
                                <div class="chart-container">
                                    <canvas :id="'chartPenggunaanAir' + blok.id"></canvas>
                                </div>
                                <div class="flex items-center justify-center gap-2 mt-4">
                                    <span class="w-4 h-4 border-3 border-[#0F92F0] bg-white flex items-center justify-center">
                                    </span>
                                    <span class="text-xs text-gray-500">Penggunaan Air</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="!isLoading && bloks.length === 0" class="text-center py-12 bg-white rounded-2xl border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-[#4F4F4F] mb-2">Tidak Ada Data</h3>
            <p class="text-sm text-gray-500">Data statistik tidak tersedia dari Firebase.</p>
        </div>
    </div>
@endsection
