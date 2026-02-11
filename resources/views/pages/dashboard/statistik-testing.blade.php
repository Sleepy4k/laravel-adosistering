@extends('layouts.dashboard')

@section('title', 'Statistik Testing')

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

        // Define statistikTestingPage synchronously
        window.statistikTestingPage = function() {
            return {
                // State
                chartsLoaded: false,
                chartsInitializing: false,
                isLoading: false, // Start with false for testing
                currentPeriod: window.__CURRENT_PERIOD__ || 'today',

                // Smoothing feature state
                useSmoothing: false,
                isSmoothingLoading: false,
                smoothedCache: {},
                rawLogs: {},
                noNumericHistory: false,

                // Testing mode
                testingMode: true, // Always true in testing page
                dummyDataGenerated: false,

                // Firebase data (for testing we simulate)
                firebaseBlocks: [],
                historyData: [],
                chartInstances: {},

                // Summary data
                summary: {
                    total_penggunaan_air: 0,
                    kelembaban_rata_rata: 0
                },

                // Processed blocks data
                bloks: [],

                async init() {
                    await this.$nextTick();

                    // Initialize with dummy Firebase blocks structure
                    this.initializeDummyFirebaseBlocks();

                    // Auto-generate dummy data on load for testing
                    console.log('🚀 Auto-generating dummy data for testing...');
                    this.generateDummyHistoryData();
                },

                initializeDummyFirebaseBlocks() {
                    // Simulate 2 blocks with sprayers
                    this.firebaseBlocks = [{
                            name: 'Block_A',
                            avgMoisture: 45.5,
                            totalVolume: 15.8,
                            avgFlowRate: 1.2,
                            sprayers: [{
                                    id: 1,
                                    moisture: 43,
                                    flow: 1.1,
                                    volume: 7.5
                                },
                                {
                                    id: 2,
                                    moisture: 48,
                                    flow: 1.3,
                                    volume: 8.3
                                }
                            ]
                        },
                        {
                            name: 'Block_B',
                            avgMoisture: 52.3,
                            totalVolume: 22.4,
                            avgFlowRate: 1.5,
                            sprayers: [{
                                    id: 1,
                                    moisture: 50,
                                    flow: 1.4,
                                    volume: 10.2
                                },
                                {
                                    id: 2,
                                    moisture: 54.6,
                                    flow: 1.6,
                                    volume: 12.2
                                }
                            ]
                        }
                    ];

                    this.processStatisticsData();
                },

                /**
                 * Generate dummy history data for testing smoothing feature
                 */
                generateDummyHistoryData() {
                    console.log('🎲 Generating dummy history data for testing...');

                    this.rawLogs = {};
                    const now = new Date();

                    // Generate for each block
                    this.firebaseBlocks.forEach(block => {
                        const blockName = block.name;
                        this.rawLogs[blockName] = [];

                        let dataPoints = [];

                        if (this.currentPeriod === 'today') {
                            // Generate 24 hours of data (every 30 minutes = 48 data points)
                            const startTime = new Date(now);
                            startTime.setHours(0, 0, 0, 0);

                            for (let i = 0; i < 48; i++) {
                                const timestamp = new Date(startTime.getTime() + (i * 30 * 60 * 1000));
                                dataPoints.push({
                                    ts: timestamp.getTime(),
                                    timestamp: this.formatTimestamp(timestamp),
                                    date: this.formatDate(timestamp)
                                });
                            }
                        } else if (this.currentPeriod === '7days') {
                            // Generate 7 days of data (every 2 hours = 84 data points)
                            const startTime = new Date(now);
                            startTime.setDate(startTime.getDate() - 7);

                            for (let i = 0; i < 84; i++) {
                                const timestamp = new Date(startTime.getTime() + (i * 2 * 60 * 60 * 1000));
                                dataPoints.push({
                                    ts: timestamp.getTime(),
                                    timestamp: this.formatTimestamp(timestamp),
                                    date: this.formatDate(timestamp)
                                });
                            }
                        } else if (this.currentPeriod === '30days') {
                            // Generate 30 days of data (every 6 hours = 120 data points)
                            const startTime = new Date(now);
                            startTime.setDate(startTime.getDate() - 30);

                            for (let i = 0; i < 120; i++) {
                                const timestamp = new Date(startTime.getTime() + (i * 6 * 60 * 60 * 1000));
                                dataPoints.push({
                                    ts: timestamp.getTime(),
                                    timestamp: this.formatTimestamp(timestamp),
                                    date: this.formatDate(timestamp)
                                });
                            }
                        }

                        console.log(`📅 Generating ${dataPoints.length} data points for ${blockName}...`);

                        // Generate ULTRA EXTREME TEST DATA - Alternating HIGH/LOW pattern
                        // This creates a ZIGZAG pattern perfect for testing smoothing
                        dataPoints.forEach((point, index) => {
                            let moisture;

                            // ZIGZAG PATTERN: Alternates between VERY HIGH and VERY LOW
                            // Index 0,2,4,6,8... → HIGH (80-95%)
                            // Index 1,3,5,7,9... → LOW (10-25%)

                            if (index % 2 === 0) {
                                // EVEN index → SPIKE HIGH
                                moisture = 80 + Math.random() * 15; // 80-95%
                            } else {
                                // ODD index → DROP LOW  
                                moisture = 10 + Math.random() * 15; // 10-25%
                            }

                            // Add occasional MEGA JUMPS (30% chance)
                            if (Math.random() > 0.70) {
                                moisture = Math.random() > 0.5 ? 92 : 12; // Jump to extreme
                            }

                            moisture = Math.max(10, Math.min(95, moisture));

                            // Flow rate - MATCHING ZIGZAG pattern
                            let flow;
                            if (index % 2 === 0) {
                                // HIGH flow matching high moisture
                                flow = 2.8 + Math.random() * 0.7; // 2.8-3.5 L/min
                            } else {
                                // LOW flow matching low moisture
                                flow = 0.2 + Math.random() * 0.3; // 0.2-0.5 L/min
                            }

                            // Random flow mega jumps
                            if (Math.random() > 0.75) {
                                flow = Math.random() > 0.5 ? 3.3 : 0.25;
                            }

                            flow = Math.max(0.2, Math.min(3.5, flow));

                            // Volume with variability
                            const timeInterval = this.currentPeriod === 'today' ? 0.5 :
                                this.currentPeriod === '7days' ? 2 : 6;
                            const volumeBase = flow * timeInterval;
                            const volumeNoise = (Math.random() - 0.5) * volumeBase * 0.4;
                            let volume = volumeBase + volumeNoise;
                            volume = Math.max(0.2, volume);

                            this.rawLogs[blockName].push({
                                ts: point.ts,
                                timestamp: point.timestamp,
                                date: point.date,
                                moisture: parseFloat(moisture.toFixed(2)),
                                flow: parseFloat(flow.toFixed(2)),
                                volume: parseFloat(volume.toFixed(2))
                            });
                        });

                        // DEBUG: Log first 5 and last 5 data points
                        console.log(`🔍 ${blockName} - First 5 points:`, this.rawLogs[blockName].slice(0, 5)
                            .map(d => d.moisture));
                        console.log(`🔍 ${blockName} - Last 5 points:`, this.rawLogs[blockName].slice(-5).map(
                            d => d.moisture));
                    });

                    this.noNumericHistory = false;
                    this.dummyDataGenerated = true;
                    
                    // IMPORTANT: Clear cache to force regeneration with new data
                    this.smoothedCache = {};
                    this.chartsLoaded = false;
                    this.chartsInitializing = false;

                    console.log('✅ Dummy data generated:', this.rawLogs);
                    console.log('📊 Total data points per block:', Object.values(this.rawLogs)[0]?.length || 0);

                    // Log sample data for verification
                    const sampleBlock = Object.keys(this.rawLogs)[0];
                    const sampleData = this.rawLogs[sampleBlock].slice(0, 10);
                    console.log('🔍 Sample data (first 10 points):', sampleData);

                    // Check data variance
                    const allMoisture = this.rawLogs[sampleBlock].map(d => d.moisture);
                    const minMoisture = Math.min(...allMoisture);
                    const maxMoisture = Math.max(...allMoisture);
                    const avgMoisture = allMoisture.reduce((a, b) => a + b, 0) / allMoisture.length;
                    console.log(
                        `📈 Moisture stats: Min=${minMoisture.toFixed(2)}%, Max=${maxMoisture.toFixed(2)}%, Avg=${avgMoisture.toFixed(2)}%`
                    );
                    console.log(
                        `📊 Variance: ${(maxMoisture - minMoisture).toFixed(2)}% (should be >30% for good testing!)`
                    );

                    // Reprocess statistics with dummy data
                    this.processStatisticsData();
                },

                /**
                 * Clear dummy data
                 */
                clearDummyData() {
                    console.log('🗑️ Clearing dummy data...');

                    this.dummyDataGenerated = false;
                    this.rawLogs = {};
                    this.noNumericHistory = true;
                    this.useSmoothing = false;

                    // Reset to initial state
                    this.initializeDummyFirebaseBlocks();

                    this.showNotification('Dummy data berhasil dihapus.', 'info');
                },

                /**
                 * Show notification
                 */
                showNotification(message, type = 'info') {
                    // Simple notification using alert (can be improved with toast library)
                    const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️';
                    alert(`${icon} ${message}`);
                },

                /**
                 * Format timestamp for dummy data
                 */
                formatTimestamp(date) {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');

                    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
                },

                /**
                 * Format date for dummy data
                 */
                formatDate(date) {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();

                    return `${day}-${month}-${year}`;
                },

                // Copy methods from original statistik page
                processStatisticsData() {
                    const processedBloks = [];
                    let totalAirKeseluruhan = 0;
                    let totalKelembabanKeseluruhan = 0;
                    let totalSprayerCount = 0;

                    this.firebaseBlocks.forEach((block, index) => {
                        const sprayerCount = block.sprayers.length || 1;
                        const moistureValues = block.sprayers.map(s => s.moisture);
                        const avgMoisture = block.avgMoisture || 0;
                        const minMoisture = moistureValues.length > 0 ? Math.min(...moistureValues) : 0;
                        const maxMoisture = moistureValues.length > 0 ? Math.max(...moistureValues) : 0;

                        let moistureStatus = 'Lembab';
                        if (avgMoisture < 30) {
                            moistureStatus = 'Kering';
                        } else if (avgMoisture > 70) {
                            moistureStatus = 'Basah';
                        }

                        const totalAirKeluar = block.totalVolume || 0;
                        const debitAirRataRata = block.avgFlowRate || 0;
                        const frekuensiIrigasi = {
                            otomatis: 5,
                            manual: 2,
                            total: 7
                        }; // Dummy frequency

                        const chartData = this.generateChartData(block);

                        console.log(`🎨 Chart data generated for ${block.name}:`, {
                            kelembabanDataPoints: chartData.kelembaban.length,
                            penggunaanAirDataPoints: chartData.penggunaanAir.length,
                            kelembabanFirst5: chartData.kelembaban.slice(0, 5),
                            penggunaanAirFirst5: chartData.penggunaanAir.slice(0, 5)
                        });

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
                        totalAirKeseluruhan += totalAirKeluar;
                        totalKelembabanKeseluruhan += avgMoisture;
                        totalSprayerCount++;
                    });

                    this.bloks = processedBloks;
                    this.summary = {
                        total_penggunaan_air: totalAirKeseluruhan,
                        kelembaban_rata_rata: totalSprayerCount > 0 ? totalKelembabanKeseluruhan /
                            totalSprayerCount : 0
                    };

                    this.$nextTick(() => {
                        if (!this.chartsInitializing) {
                            this.initializeCharts();
                        }
                    });
                },

                generateChartData(block) {
                    const now = new Date();
                    const currentMoisture = block.avgMoisture || 0;
                    const currentVolume = block.totalVolume || 0;
                    const hasRealData = currentMoisture > 0 || currentVolume > 0;

                    console.log(
                        `🎯 generateChartData for ${block.name}: useSmoothing=${this.useSmoothing}, hasRawLogs=${!!(this.rawLogs[block.name] && this.rawLogs[block.name].length > 0)}`
                    );

                    const cacheKey = this.getCacheKey(block.name, this.useSmoothing);
                    if (this.smoothedCache[cacheKey]) {
                        console.log(`💾 Using cached data for ${block.name}`);
                        return this.smoothedCache[cacheKey];
                    }

                    if (this.useSmoothing && this.rawLogs[block.name] && this.rawLogs[block.name].length > 0) {
                        console.log(`🔄 SMOOTHING MODE ACTIVE for ${block.name}!`);
                        const aggregated = this.aggregateByPeriod(this.rawLogs[block.name], this.currentPeriod);

                        if (aggregated.labels.length > 0) {
                            const window = this.getSmoothingWindow();
                            console.log(`📊 Applying moving average with window size: ${window}`);
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
                            console.log(`✅ Smoothed data cached for ${block.name}`);
                            return result;
                        }
                    }

                    console.log(`📍 Using RAW chart data for ${block.name}`);
                    return this.generateRawChartData(block, now, currentMoisture, currentVolume, hasRealData);
                },

                generateRawChartData(block, now, currentMoisture, currentVolume, hasRealData) {
                    let labels = [];
                    let kelembabanData = [];
                    let penggunaanAirData = [];

                    // IMPORTANT: Use rawLogs if available (from generated dummy data)
                    if (this.rawLogs[block.name] && this.rawLogs[block.name].length > 0) {
                        console.log(`📌 Using rawLogs for ${block.name} in raw chart data`);
                        const aggregated = this.aggregateByPeriod(this.rawLogs[block.name], this.currentPeriod);

                        return {
                            kelembaban: aggregated.labels.map((waktu, idx) => ({
                                waktu: waktu,
                                nilai: aggregated.moistureValues[idx]
                            })),
                            penggunaanAir: aggregated.labels.map((waktu, idx) => ({
                                waktu: waktu,
                                nilai: aggregated.volumeValues[idx]
                            }))
                        };
                    }

                    // Fallback: Use hardcoded noisy data for testing
                    console.log(`⚠️ No rawLogs found for ${block.name}, using hardcoded noisy data`);

                    if (this.currentPeriod === 'today') {
                        // HARDCODED NOISY DATA for better visualization
                        const hourlyData = [{
                                hour: '00:00',
                                moisture: 12.4,
                                volume: 0.1
                            }, // sangat kering
                            {
                                hour: '02:00',
                                moisture: 85.9,
                                volume: 4.8
                            }, // spike irigasi ekstrem
                            {
                                hour: '04:00',
                                moisture: 18.2,
                                volume: 0.2
                            }, // drop tajam
                            {
                                hour: '06:00',
                                moisture: 92.5,
                                volume: 5.6
                            }, // over-irrigation
                            {
                                hour: '08:00',
                                moisture: 27.1,
                                volume: 0.3
                            },
                            {
                                hour: '10:00',
                                moisture: 76.4,
                                volume: 3.9
                            },
                            {
                                hour: '12:00',
                                moisture: 14.8,
                                volume: 0.1
                            },
                            {
                                hour: '14:00',
                                moisture: 88.7,
                                volume: 5.2
                            },
                            {
                                hour: '16:00',
                                moisture: 22.3,
                                volume: 0.2
                            },
                            {
                                hour: '18:00',
                                moisture: 79.6,
                                volume: 4.1
                            },
                            {
                                hour: '20:00',
                                moisture: 16.9,
                                volume: 0.1
                            },
                            {
                                hour: '22:00',
                                moisture: 90.2,
                                volume: 5.4
                            }
                        ];


                        const currentHour = now.getHours();
                        hourlyData.forEach(item => {
                            const hour = parseInt(item.hour.split(':')[0]);
                            if (hour <= currentHour) {
                                labels.push(item.hour);
                                kelembabanData.push(item.moisture);
                                penggunaanAirData.push(item.volume);
                            }
                        });
                    } else if (this.currentPeriod === '7days') {
                        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

                        const dailyData = [
                            21.3, // Min – kekeringan
                            88.9, // Sen – irigasi masif
                            26.4, // Sel – drop
                            91.7, // Rab – over saturated
                            19.8, // Kam – kritis kering
                            84.5, // Jum – spike
                            33.2 // Sab – recovery parsial
                        ];
                        const volumeData = [
                            1.2, // Min – hampir tidak irigasi
                            38.5, // Sen – boros parah
                            2.1, // Sel
                            42.7, // Rab – flood mode
                            0.9, // Kam – sistem hampir mati
                            35.6, // Jum – heavy irrigation
                            4.3 // Sab – stabilisasi
                        ];


                        for (let i = 6; i >= 0; i--) {
                            const date = new Date(now);
                            date.setDate(date.getDate() - i);
                            labels.push(days[date.getDay()]);
                            kelembabanData.push(dailyData[6 - i]);
                            penggunaanAirData.push(volumeData[6 - i]);
                        }
                    } else if (this.currentPeriod === '30days') {
                        // EXTREME weekly data - zigzag pattern (4 minggu)
                        const weeklyData = [18.5, 87.2, 23.1, 91.8]; // Extreme moisture fluctuations
                        const volumeData = [2.1, 52.7, 3.8, 61.4]; // Extreme volume fluctuations

                        for (let i = 3; i >= 0; i--) {
                            labels.push('Minggu ' + (4 - i));
                            kelembabanData.push(weeklyData[3 - i]);
                            penggunaanAirData.push(volumeData[3 - i]);
                        }
                    }

                    const result = {
                        kelembaban: labels.map((waktu, idx) => ({
                            waktu,
                            nilai: kelembabanData[idx]
                        })),
                        penggunaanAir: labels.map((waktu, idx) => ({
                            waktu,
                            nilai: penggunaanAirData[idx]
                        }))
                    };

                    this.smoothedCache[this.getCacheKey(block.name, false)] = result;
                    return result;
                },

                async initializeCharts() {
                    if (this.chartsInitializing || this.bloks.length === 0) {
                        console.log('⏭️ Skipping chart initialization (already initializing or no blocks)');
                        return;
                    }

                    console.log('🎨 Starting chart initialization...');
                    this.chartsInitializing = true;

                    try {
                        // Load Chart.js first if not loaded
                        const chartLoaderPath = window.__CHART_LOADER_PATH__;
                        console.log('📦 Loading chart module...');
                        await import(chartLoaderPath);
                        
                        const chartModule = window.ChartModule;
                        
                        // Destroy existing charts using ChartModule (guaranteed to have Chart access)
                        if (chartModule && typeof chartModule.destroyAllCharts === 'function') {
                            chartModule.destroyAllCharts(this.bloks);
                        }

                        // Wait to ensure cleanup is complete
                        await new Promise(resolve => setTimeout(resolve, 100));

                        if (chartModule && typeof chartModule.initializeCharts === 'function') {
                            console.log('✨ Creating charts for', this.bloks.length, 'blocks...');
                            chartModule.initializeCharts(this.bloks);
                            this.chartsLoaded = true;
                            console.log('✅ Charts initialized successfully');
                        }
                    } catch (error) {
                        console.error('❌ Failed to load charts:', error);
                    } finally {
                        this.chartsInitializing = false;
                    }
                },

                destroyAllCharts() {
                    console.log('🗑️ Destroying all existing charts...');

                    try {
                        // Use ChartModule.destroyAllCharts if available (preferred method)
                        const chartModule = window.ChartModule;
                        if (chartModule && typeof chartModule.destroyAllCharts === 'function') {
                            chartModule.destroyAllCharts(this.bloks);
                            this.chartInstances = {};
                            this.chartsLoaded = false;
                            return;
                        }
                        
                        // Fallback: Check if Chart.js is loaded via window.Chart
                        if (typeof window.Chart === 'undefined') {
                            console.log('  ⚠️ Chart.js not loaded yet, skipping destroy');
                            this.chartInstances = {};
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
                                            if (chart) {
                                                console.log(`  ✓ Destroying chart on canvas: ${canvasId}`);
                                                chart.destroy();
                                            }
                                        } catch (e) {
                                            console.log(`  ⚠️ Could not get chart for ${canvasId}:`, e.message);
                                        }
                                    }
                                });
                            });
                        }

                        console.log('✅ All charts destroyed successfully');
                    } catch (error) {
                        console.error('⚠️ Error destroying charts:', error);
                    }

                    this.chartInstances = {};
                    this.chartsLoaded = false;
                },

                async toggleSmoothing() {
                    if (!this.useSmoothing && this.noNumericHistory) {
                        this.showNotification(
                            'Tidak ada data history. Silakan generate dummy data terlebih dahulu.', 'error');
                        return;
                    }

                    console.log(
                        `🔀 TOGGLE SMOOTHING: Current state=${this.useSmoothing}, switching to=${!this.useSmoothing}`
                    );

                    this.isSmoothingLoading = true;

                    try {
                        // Toggle the smoothing state
                        this.useSmoothing = !this.useSmoothing;

                        // IMPORTANT: Clear cache to force regeneration
                        console.log(`🗑️ Clearing cache to force chart regeneration...`);
                        this.smoothedCache = {};

                        // Reset chart states BEFORE destroying
                        this.chartsLoaded = false;
                        this.chartsInitializing = false;

                        // Wait a bit before reprocessing
                        await new Promise(resolve => setTimeout(resolve, 50));

                        console.log(`♻️ Reprocessing statistics with useSmoothing=${this.useSmoothing}...`);
                        this.processStatisticsData();
                    } catch (error) {
                        console.error('Error toggling smoothing:', error);
                    } finally {
                        this.isSmoothingLoading = false;
                    }
                },

                smoothMovingAverage(arr, window = 5) {
                    console.log(`🔧 smoothMovingAverage called: window=${window}, input length=${arr.length}`);
                    console.log(`📥 Input (first 10):`, arr.slice(0, 10));

                    const out = new Array(arr.length).fill(null);
                    const sanitized = arr.map(v => (v === undefined || v === null || isNaN(v)) ? null : parseFloat(v));

                    for (let i = 0; i < sanitized.length; i++) {
                        const start = Math.max(0, i - window + 1);
                        const slice = sanitized.slice(start, i + 1).filter(v => v !== null);
                        if (slice.length === 0) continue;
                        out[i] = slice.reduce((a, b) => a + b, 0) / slice.length;
                    }

                    console.log(`📤 Output (first 10):`, out.slice(0, 10));
                    console.log(
                        `✅ Smoothing complete. Input variance: ${(Math.max(...sanitized.filter(v => v !== null)) - Math.min(...sanitized.filter(v => v !== null))).toFixed(2)}, Output variance: ${(Math.max(...out.filter(v => v !== null)) - Math.min(...out.filter(v => v !== null))).toFixed(2)}`
                    );

                    return out;
                },

                aggregateByPeriod(rawLogs, period) {
                    const now = new Date();
                    const result = {
                        labels: [],
                        moistureValues: [],
                        volumeValues: []
                    };

                    console.log(`📊 aggregateByPeriod called: period=${period}, rawLogs count=${rawLogs?.length || 0}`);

                    if (!rawLogs || rawLogs.length === 0) {
                        console.warn('⚠️ No raw logs available for aggregation!');
                        return result;
                    }

                    let startDate = new Date(now);
                    if (period === 'today') {
                        startDate.setHours(0, 0, 0, 0);
                    } else if (period === '7days') {
                        startDate.setDate(startDate.getDate() - 7);
                        startDate.setHours(0, 0, 0, 0);
                    } else if (period === '30days') {
                        startDate.setDate(startDate.getDate() - 30);
                        startDate.setHours(0, 0, 0, 0);
                    }

                    const filteredLogs = rawLogs.filter(log => log.ts >= startDate.getTime());
                    console.log(`📅 Filtered ${filteredLogs.length} logs from ${rawLogs.length} total`);

                    if (period === 'today') {
                        const hourlyBuckets = {};
                        for (let h = 0; h <= now.getHours(); h += 2) {
                            hourlyBuckets[h] = {
                                moisture: [],
                                volume: []
                            };
                        }

                        filteredLogs.forEach(log => {
                            const hour = Math.floor(new Date(log.ts).getHours() / 2) * 2;
                            if (hourlyBuckets[hour]) {
                                if (log.moisture !== null) hourlyBuckets[hour].moisture.push(log.moisture);
                                if (log.volume !== null) hourlyBuckets[hour].volume.push(log.volume);
                            }
                        });

                        console.log('🪣 Hourly buckets raw data:', Object.entries(hourlyBuckets).map(([hour, data]) =>
                            ({
                                hour,
                                moistureCount: data.moisture.length,
                                moistureFirst5: data.moisture.slice(0, 5),
                                volumeCount: data.volume.length,
                                volumeFirst5: data.volume.slice(0, 5)
                            })));

                        Object.keys(hourlyBuckets).sort((a, b) => a - b).forEach(hour => {
                            const bucket = hourlyBuckets[hour];
                            const avgMoisture = bucket.moisture.length > 0 ? bucket.moisture.reduce((a, b) =>
                                a + b, 0) / bucket.moisture.length : null;
                            const avgVolume = bucket.volume.length > 0 ? bucket.volume.reduce((a, b) => a + b,
                                0) / bucket.volume.length : null;

                            result.labels.push(hour.toString().padStart(2, '0') + ':00');
                            result.moistureValues.push(avgMoisture);
                            result.volumeValues.push(avgVolume);
                        });

                        console.log('📈 Aggregated moisture values (hourly):', result.moistureValues);
                        console.log('📈 Aggregated volume values (hourly):', result.volumeValues);
                    } else if (period === '7days') {
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
                            const dateKey = new Date(log.ts).toISOString().split('T')[0];
                            if (dailyBuckets[dateKey]) {
                                if (log.moisture !== null) dailyBuckets[dateKey].moisture.push(log.moisture);
                                if (log.volume !== null) dailyBuckets[dateKey].volume.push(log.volume);
                            }
                        });

                        Object.keys(dailyBuckets).sort().forEach(dateKey => {
                            const bucket = dailyBuckets[dateKey];
                            result.labels.push(bucket.dayName);
                            result.moistureValues.push(bucket.moisture.length > 0 ? bucket.moisture.reduce((a,
                                b) => a + b, 0) / bucket.moisture.length : null);
                            result.volumeValues.push(bucket.volume.length > 0 ? bucket.volume.reduce((a, b) =>
                                a + b, 0) / bucket.volume.length : null);
                        });
                    } else if (period === '30days') {
                        // 4 minggu untuk 30 hari
                        const weeklyBuckets = {};
                        for (let w = 3; w >= 0; w--) {
                            weeklyBuckets[w] = {
                                moisture: [],
                                volume: []
                            };
                        }

                        filteredLogs.forEach(log => {
                            const daysDiff = Math.floor((now.getTime() - log.ts) / (1000 * 60 * 60 * 24));
                            const weekIndex = Math.min(3, Math.floor(daysDiff / 7));
                            if (weeklyBuckets[weekIndex]) {
                                if (log.moisture !== null) weeklyBuckets[weekIndex].moisture.push(log.moisture);
                                if (log.volume !== null) weeklyBuckets[weekIndex].volume.push(log.volume);
                            }
                        });

                        [3, 2, 1, 0].forEach(w => {
                            const bucket = weeklyBuckets[w];
                            result.labels.push('Minggu ' + (4 - w));
                            result.moistureValues.push(bucket.moisture.length > 0 ? bucket.moisture.reduce((a,
                                b) => a + b, 0) / bucket.moisture.length : null);
                            result.volumeValues.push(bucket.volume.length > 0 ? bucket.volume.reduce((a, b) =>
                                a + b, 0) / bucket.volume.length : null);
                        });
                    }

                    return result;
                },

                getCacheKey(blockName, isSmoothed) {
                    return `${blockName}::${this.currentPeriod}::${isSmoothed ? 'sm' : 'raw'}`;
                },

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
                    switch (status) {
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

    <div class="max-w-7xl mx-auto" x-data="statistikTestingPage()">

        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-[#4F4F4F]">Statistik Dashboard</h2>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const now = new Date();
                    currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate">
                    </p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" loading="lazy"
                        class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>


        <!-- Period Filter Tabs -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Period Tabs -->
                <div class="flex items-center justify-center gap-6">
                    <a href="{{ route('user.statistik.testing', ['period' => 'today']) }}"
                        class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === 'today' ? 'text-primary underline underline-offset-8 decoration-2 decoration-primary' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('user.statistik.testing', ['period' => '7days']) }}"
                        class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === '7days' ? 'text-primary underline underline-offset-8 decoration-2 decoration-primary' : 'text-gray-500 hover:text-gray-700' }}">
                        7 Hari Terakhir
                    </a>
                    <a href="{{ route('user.statistik.testing', ['period' => '30days']) }}"
                        class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === '30days' ? 'text-primary underline underline-offset-8 decoration-2 decoration-primary' : 'text-gray-500 hover:text-gray-700' }}">
                        30 Hari Terakhir
                    </a>
                </div>

                <!-- Data Mode Toggle -->
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Mode Data:</span>

                    <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-xl">
                        <!-- Raw Button -->
                        <button type="button" x-on:click="if(useSmoothing) { toggleSmoothing(); }"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer"
                            :class="!useSmoothing ? 'bg-white text-primary shadow-sm border border-primary' :
                                'bg-transparent text-gray-500 hover:text-gray-700'">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Data Mentah
                            </span>
                        </button>

                        <!-- Smoothed Button -->
                        <button type="button" x-on:click="if(!useSmoothing && !noNumericHistory) { toggleSmoothing(); }"
                            :disabled="noNumericHistory"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                            :class="[
                                noNumericHistory ?
                                'bg-gray-200 text-gray-400 cursor-not-allowed' :
                                useSmoothing ?
                                'bg-primary text-white shadow-sm cursor-pointer' :
                                'bg-transparent text-gray-500 hover:text-gray-700 cursor-pointer'
                            ]">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                    </path>
                                </svg>
                                Smoothing
                            </span>
                        </button>
                    </div>

                    <!-- Loading indicator -->
                    <div x-show="isSmoothingLoading" class="flex items-center" style="display: none;">
                        <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>

                    <!-- Mode indicator -->
                    <span x-show="useSmoothing"
                        class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full"
                        style="display: none;">
                        🟢 Smoothing Active
                    </span>
                    <span x-show="!useSmoothing && dummyDataGenerated"
                        class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                        🔵 Raw Mode
                    </span>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Total Penggunaan Air Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    <span x-text="formatNumber(summary.total_penggunaan_air)"></span>
                    <span class="text-lg font-normal">Liter</span>
                </p>
            </div>

            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Kelembaban Rata Rata Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    <span x-text="formatNumber(summary.kelembaban_rata_rata)"></span>%
                </p>
            </div>
        </div>

        <!-- Blok Sections -->
        <template x-for="(blok, index) in bloks" :key="blok.id">
            <div class="bg-white rounded-2xl border border-[#C2C2C2] mb-6 overflow-hidden" x-data="{ expanded: index === 0 }">
                <button @click="expanded = !expanded"
                    class="w-full py-4 px-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <h2 class="text-lg font-semibold text-[#4F4F4F]" x-text="blok.nama"></h2>
                    <svg class="w-5 h-5 text-gray-500 transition-transform duration-200"
                        :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>

                <div x-show="expanded" x-collapse>
                    <div class="px-6 pb-6">
                        <!-- Statistik Cards -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Frekuensi Irigasi Aktif</p>
                                <p class="text-4xl font-bold text-primary-darker mb-4"
                                    x-text="blok.frekuensi_irigasi.total"></p>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#BFDBFE] bg-[#EFF6FF] text-info-blue text-xs font-medium">
                                        <span x-text="blok.frekuensi_irigasi.otomatis"></span>x Otomatis
                                    </span>
                                    <span
                                        class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#E5E5E5] bg-[#FAFAFA] text-[#525252] text-xs font-medium">
                                        <span x-text="blok.frekuensi_irigasi.manual"></span>x Manual
                                    </span>
                                </div>
                            </div>

                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Kelembaban Rata Rata</p>
                                <div class="flex items-center gap-2 mb-4">
                                    <p class="text-4xl font-bold text-primary-darker">
                                        <span x-text="formatNumber(blok.kelembaban.rata_rata)"></span>%
                                    </p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md border text-xs font-medium"
                                        :class="getMoistureStatusClass(blok.kelembaban.status)"
                                        x-text="blok.kelembaban.status">
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#FEE2E2] bg-[#FEF2F2] text-[#DC2626] text-xs font-medium">
                                        <span x-text="formatNumber(blok.kelembaban.min, 0)"></span>%
                                    </span>
                                    <span class="text-gray-400">-</span>
                                    <span
                                        class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#D3F3DF] bg-[#F2FDF5] text-[#16A34A] text-xs font-medium">
                                        <span x-text="formatNumber(blok.kelembaban.max, 0)"></span>%
                                    </span>
                                </div>
                            </div>

                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Total Air Keluar</p>
                                <p class="text-4xl font-bold text-primary-darker mb-4">
                                    <span x-text="formatNumber(blok.total_air_digunakan)"></span>
                                </p>
                                <p class="text-sm text-[#4F4F4F]">Liter</p>
                            </div>

                            <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                                <p class="text-sm text-gray-500 mb-3">Debit Air Rata Rata</p>
                                <p class="text-4xl font-bold text-primary-darker mb-4">
                                    <span x-text="formatNumber(blok.debit_air_rata_rata)"></span>
                                </p>
                                <p class="text-sm text-[#4F4F4F]">Liter/menit</p>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="bg-white border border-[#C2C2C2] rounded-xl p-4">
                                <h3 class="text-base font-semibold text-[#4F4F4F] mb-4">Kelembaban Tanah</h3>
                                <div class="chart-container">
                                    <canvas :id="'chartKelembaban' + blok.id"></canvas>
                                </div>
                                <div class="flex items-center justify-center gap-2 mt-4">
                                    <span class="w-4 h-4 border-3 border-primary bg-white"></span>
                                    <span class="text-xs text-gray-500">Kelembaban Tanah</span>
                                </div>
                            </div>

                            <div class="bg-white border border-[#C2C2C2] rounded-xl p-4">
                                <h3 class="text-base font-semibold text-[#4F4F4F] mb-4">Penggunaan Air</h3>
                                <div class="chart-container">
                                    <canvas :id="'chartPenggunaanAir' + blok.id"></canvas>
                                </div>
                                <div class="flex items-center justify-center gap-2 mt-4">
                                    <span class="w-4 h-4 border-3 border-[#0F92F0] bg-white"></span>
                                    <span class="text-xs text-gray-500">Penggunaan Air</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="bloks.length === 0" class="text-center py-12 bg-white rounded-2xl border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
            <h3 class="text-lg font-semibold text-[#4F4F4F] mb-2">Tidak Ada Data</h3>
            <p class="text-sm text-gray-500">Klik tombol "Generate Dummy Data" di atas untuk memulai testing.</p>
        </div>
    </div>
@endsection
