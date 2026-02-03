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
                                // Skip non-sprayer keys (control, data, etc)
                                if (!sprayerName.includes('Sprayer')) return;
                                
                                const sprayerData = blockData[sprayerName];
                                if (!sprayerData || !sprayerData.history) return;
                                
                                // Loop through history dates: Sprayer_1 -> history -> 01-02-2026, 02-02-2026, etc
                                Object.keys(sprayerData.history).forEach(dateKey => {
                                    const dateData = sprayerData.history[dateKey];
                                    if (!dateData || typeof dateData !== 'object') return;
                                    
                                    // Count unique IDs for this date
                                    const uniqueIds = Object.keys(dateData);
                                    
                                    // Loop through each unique ID: 01-02-2026 -> -OkNNb3Axdpd0D0C9EYc, -OkNNpnnI4S8WXtuCsGy, etc
                                    uniqueIds.forEach(uniqueId => {
                                        const entry = dateData[uniqueId];
                                        if (!entry || typeof entry !== 'object') return;
                                        
                                        // Use entry.timestamp if exists, otherwise use dateKey
                                        const timestamp = entry.timestamp || dateKey;
                                        
                                        // Each unique ID = 1 irrigation event
                                        historyEntries.push({
                                            blockName: blockName,
                                            sprayerName: sprayerName,
                                            date: dateKey,
                                            uniqueId: uniqueId,
                                            timestamp: timestamp,
                                            jenisIrigasi: entry.jenisIrigasi || 'Otomatis' // Default karena belum ada field di Firebase
                                        });
                                    });
                                });
                            });
                        });
                        
                        this.historyData = historyEntries;
                        
                        // Reprocess statistics with history data
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
                    let labels = [];
                    let kelembabanData = [];
                    let penggunaanAirData = [];
                    
                    // Get current real values from block (NO SIMULATION)
                    const currentMoisture = block.avgMoisture || 0;
                    const currentVolume = block.totalVolume || 0;
                    const avgFlowRate = block.avgFlowRate || 0;
                    
                    // Check if we have real data (not just zeros)
                    const hasRealData = currentMoisture > 0 || currentVolume > 0;
                    
                    if (this.currentPeriod === 'today') {
                        // Per jam untuk hari ini (24 jam) - DATA MENTAH
                        const currentHour = now.getHours();
                        for (let i = 0; i <= currentHour; i += 2) {
                            const hour = i.toString().padStart(2, '0') + ':00';
                            labels.push(hour);
                            
                            // Only add data if we have real data, otherwise null (chart won't draw)
                            kelembabanData.push(hasRealData ? currentMoisture : null);
                            penggunaanAirData.push(hasRealData ? currentVolume : null);
                        }
                    } else if (this.currentPeriod === '7days') {
                        // Per hari untuk 7 hari terakhir - DATA MENTAH
                        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                        for (let i = 6; i >= 0; i--) {
                            const date = new Date(now);
                            date.setDate(date.getDate() - i);
                            const dayName = days[date.getDay()];
                            labels.push(dayName);
                            
                            // Only add data for today if we have real data
                            if (i === 0 && hasRealData) {
                                kelembabanData.push(currentMoisture);
                                penggunaanAirData.push(currentVolume);
                            } else {
                                // No data for past days - use null so chart won't draw
                                kelembabanData.push(null);
                                penggunaanAirData.push(null);
                            }
                        }
                    } else if (this.currentPeriod === '30days') {
                        // Per minggu untuk 30 hari terakhir - DATA MENTAH
                        for (let i = 4; i >= 0; i--) {
                            const weekLabel = 'Minggu ' + (5 - i);
                            labels.push(weekLabel);
                            
                            // Only add data for current week if we have real data
                            if (i === 0 && hasRealData) {
                                kelembabanData.push(currentMoisture);
                                penggunaanAirData.push(currentVolume);
                            } else {
                                // No data for past weeks - use null so chart won't draw
                                kelembabanData.push(null);
                                penggunaanAirData.push(null);
                            }
                        }
                    }
                    
                    return {
                        kelembaban: labels.map((waktu, idx) => ({
                            waktu: waktu,
                            nilai: kelembabanData[idx]
                        })),
                        penggunaanAir: labels.map((waktu, idx) => ({
                            waktu: waktu,
                            nilai: penggunaanAirData[idx]
                        }))
                    };
                },
                
                async initializeCharts() {
                    // Prevent multiple simultaneous initialization
                    if (this.chartsInitializing) {
                        return;
                    }
                    
                    if (this.chartsLoaded) {
                        return;
                    }
                    
                    if (this.bloks.length === 0) {
                        return;
                    }
                    
                    this.chartsInitializing = true;
                    
                    try {
                        // First, destroy ALL existing Chart.js instances globally
                        this.destroyAllCharts();
                        
                        // Wait a bit for cleanup
                        await new Promise(resolve => setTimeout(resolve, 100));
                        
                        const chartLoaderPath = window.__CHART_LOADER_PATH__;
                        
                        // Import the chart module
                        await import(chartLoaderPath);
                        
                        const chartModule = window.ChartModule;
                        
                        if (chartModule && typeof chartModule.initializeCharts === 'function') {
                            chartModule.initializeCharts(this.bloks);
                            this.chartsLoaded = true;
                        }
                    } catch (error) {
                        console.error('Failed to load charts:', error);
                    } finally {
                        this.chartsInitializing = false;
                    }
                },
                
                destroyAllCharts() {
                    try {
                        // Method 1: Destroy by canvas ID
                        if (this.bloks && this.bloks.length > 0) {
                            this.bloks.forEach(blok => {
                                const canvasIds = [
                                    'chartKelembaban' + blok.id,
                                    'chartPenggunaanAir' + blok.id
                                ];
                                
                                canvasIds.forEach(canvasId => {
                                    const canvas = document.getElementById(canvasId);
                                    if (canvas && typeof Chart !== 'undefined') {
                                        const existingChart = Chart.getChart(canvas);
                                        if (existingChart) {
                                            existingChart.destroy();
                                        }
                                    }
                                });
                            });
                        }
                        
                        // Method 2: Destroy all Chart.js instances globally
                        if (typeof Chart !== 'undefined' && Chart.instances) {
                            Object.keys(Chart.instances).forEach(key => {
                                const chart = Chart.instances[key];
                                if (chart) {
                                    chart.destroy();
                                }
                            });
                        }
                        
                        // Method 3: Use Chart.helpers if available
                        if (typeof Chart !== 'undefined' && Chart.helpers && Chart.helpers.each) {
                            Chart.helpers.each(Chart.instances, function(instance) {
                                if (instance) {
                                    instance.destroy();
                                }
                            });
                        }
                        
                    } catch (error) {
                        console.error('Error destroying charts:', error);
                    }
                    
                    this.chartInstances = {};
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
