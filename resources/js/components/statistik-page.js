/**
 * Statistik Page Alpine.js Component
 * Extracted from inline script in statistik.blade.php
 *
 * Usage in Blade: x-data="statistikPage()"
 * Requires:
 *   - window.__CHART_LOADER_PATH__   (Vite asset path)
 *   - window.__FIREBASE_MODULE_PATH__ (Vite asset path)
 *   - window.__FIREBASE_CONFIG__      (Laravel config object)
 *   - window.__CURRENT_PERIOD__       ('today' | '7days' | '30days')
 */

window.statistikPage = function () {
    return {
        // State
        chartsLoaded: false,
        chartsInitializing: false,
        isLoading: true,
        currentPeriod: window.__CURRENT_PERIOD__ || 'today',

        // Smoothing feature state
        useSmoothing: false,
        isSmoothingLoading: false,
        smoothedCache: {},
        rawLogs: {},
        noNumericHistory: false,

        // Firebase data
        firebaseBlocks: [],
        historyData: [],
        chartInstances: {},

        // Summary data
        summary: {
            total_penggunaan_air: 0,
            kelembaban_rata_rata: 0,
        },

        // Processed blocks data
        bloks: [],

        async init() {
            await this.$nextTick();
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

                await import(firebaseModulePath);

                const firebaseModule = window.FirebaseModule;

                if (!firebaseModule || typeof firebaseModule.initializeFirebase !== 'function') {
                    console.error('Firebase module not found on window.FirebaseModule');
                    this.isLoading = false;
                    return;
                }

                const firebaseConfig = {
                    apiKey: config.api_key,
                    authDomain: config.auth_domain,
                    databaseURL: config.database_url,
                    projectId: config.project_id,
                    storageBucket: config.storage_bucket,
                    messagingSenderId: config.messaging_sender_id,
                    appId: config.app_id,
                };

                const db = firebaseModule.initializeFirebase(firebaseConfig);

                if (!db) {
                    console.error('Firebase database initialization failed');
                    this.isLoading = false;
                    return;
                }

                firebaseModule.listenToMAOS((result) => {
                    if (result.blocks && result.blocks.length > 0) {
                        this.firebaseBlocks = result.blocks;

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
                const firebaseModule = window.FirebaseModule;

                if (!firebaseModule) {
                    console.error('Firebase module not available for history loading');
                    return;
                }

                const maosData = await firebaseModule.getData('MAOS');

                if (!maosData) {
                    return;
                }

                const historyEntries = [];

                Object.keys(maosData).forEach((blockName) => {
                    const blockData = maosData[blockName];
                    if (!blockData || typeof blockData !== 'object') return;

                    Object.keys(blockData).forEach((sprayerName) => {
                        if (!sprayerName.includes('Sprayer')) return;

                        const sprayerData = blockData[sprayerName];
                        if (!sprayerData) return;

                        if (sprayerData.history && typeof sprayerData.history === 'object') {
                            Object.keys(sprayerData.history).forEach((dateKey) => {
                                const dateData = sprayerData.history[dateKey];
                                if (!dateData || typeof dateData !== 'object') return;

                                const uniqueIds = Object.keys(dateData);

                                uniqueIds.forEach((uniqueId) => {
                                    const entry = dateData[uniqueId];
                                    if (!entry || typeof entry !== 'object') return;

                                    const timestamp = entry.timestamp || dateKey;

                                    historyEntries.push({
                                        blockName: blockName,
                                        sprayerName: sprayerName,
                                        date: dateKey,
                                        uniqueId: uniqueId,
                                        timestamp: timestamp,
                                        jenisIrigasi: entry.jenisIrigasi || 'Otomatis',
                                    });
                                });
                            });
                        }
                    });
                });

                this.historyData = historyEntries;

                if (this.firebaseBlocks.length > 0) {
                    this.processStatisticsData();
                }
            } catch (error) {
                console.error('Failed to load history data:', error);
            }
        },

        processStatisticsData() {
            const processedBloks = [];
            let totalAirKeseluruhan = 0;
            let totalKelembabanKeseluruhan = 0;
            let totalSprayerCount = 0;

            this.firebaseBlocks.forEach((block, index) => {
                const sprayerCount = block.sprayers.length || 1;

                const moistureValues = block.sprayers.map((s) => s.moisture);
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
                const frekuensiIrigasi = this.calculateIrrigationFrequency(block.name);
                const chartData = this.generateChartData(block);

                const blokData = {
                    id: index + 1,
                    nama: block.name,
                    frekuensi_irigasi: frekuensiIrigasi,
                    kelembaban: {
                        rata_rata: avgMoisture,
                        min: minMoisture,
                        max: maxMoisture,
                        status: moistureStatus,
                    },
                    kelembaban_rata_rata: avgMoisture,
                    total_air_digunakan: totalAirKeluar,
                    debit_air_rata_rata: debitAirRataRata,
                    chart_kelembaban: chartData.kelembaban,
                    chart_penggunaan_air: chartData.penggunaanAir,
                    sprayers: block.sprayers,
                };

                processedBloks.push(blokData);

                totalAirKeseluruhan += totalAirKeluar;
                totalKelembabanKeseluruhan += avgMoisture;
                totalSprayerCount++;
            });

            this.bloks = processedBloks;

            this.summary = {
                total_penggunaan_air: totalAirKeseluruhan,
                kelembaban_rata_rata: totalSprayerCount > 0 ? totalKelembabanKeseluruhan / totalSprayerCount : 0,
            };

            this.$nextTick(() => {
                if (!this.chartsInitializing) {
                    this.initializeCharts();
                }
            });
        },

        calculateIrrigationFrequency(blockName) {
            const now = new Date();
            let startDate;

            if (this.currentPeriod === 'today') {
                startDate = new Date(now);
                startDate.setHours(now.getHours() - 24);
            } else if (this.currentPeriod === '7days') {
                startDate = new Date(now);
                startDate.setDate(startDate.getDate() - 7);
            } else if (this.currentPeriod === '30days') {
                startDate = new Date(now);
                startDate.setDate(startDate.getDate() - 30);
            }

            const blockHistory = this.historyData.filter((entry) => {
                if (entry.blockName !== blockName) return false;

                const entryDate = this.parseHistoryTimestamp(entry.timestamp);
                if (!entryDate) return false;

                return entryDate >= startDate && entryDate <= now;
            });

            const otomatis = blockHistory.filter((entry) => entry.jenisIrigasi === 'Otomatis').length;
            const manual = blockHistory.filter((entry) => entry.jenisIrigasi === 'Manual').length;

            return {
                otomatis: otomatis,
                manual: manual,
                total: blockHistory.length,
            };
        },

        parseHistoryTimestamp(timestampStr) {
            if (!timestampStr) return null;

            const parts = timestampStr.split(' ');
            const dateParts = parts[0].split('-');

            if (dateParts.length !== 3) return null;

            const day = parseInt(dateParts[0]);
            const month = parseInt(dateParts[1]) - 1;
            const year = parseInt(dateParts[2]);

            if (parts.length > 1) {
                const timeParts = parts[1].split(':');
                const hour = parseInt(timeParts[0]) || 0;
                const minute = parseInt(timeParts[1]) || 0;
                const second = parseInt(timeParts[2]) || 0;

                return new Date(year, month, day, hour, minute, second);
            } else {
                return new Date(year, month, day);
            }
        },

        generateChartData(block) {
            const now = new Date();
            const currentMoisture = block.avgMoisture || 0;
            const currentVolume = block.totalVolume || 0;
            const hasRealData = currentMoisture > 0 || currentVolume > 0;

            const cacheKey = this.getCacheKey(block.name, this.useSmoothing);
            if (this.smoothedCache[cacheKey]) {
                return this.smoothedCache[cacheKey];
            }

            if (this.useSmoothing && this.rawLogs[block.name] && this.rawLogs[block.name].length > 0) {
                const aggregated = this.aggregateByPeriod(this.rawLogs[block.name], this.currentPeriod);

                if (aggregated.labels.length > 0) {
                    const win = this.getSmoothingWindow();
                    const kelembabanData = this.smoothMovingAverage(aggregated.moistureValues, win);
                    const penggunaanAirData = this.smoothMovingAverage(aggregated.volumeValues, win);

                    const result = {
                        kelembaban: aggregated.labels.map((waktu, idx) => ({
                            waktu: waktu,
                            nilai: kelembabanData[idx],
                        })),
                        penggunaanAir: aggregated.labels.map((waktu, idx) => ({
                            waktu: waktu,
                            nilai: penggunaanAirData[idx],
                        })),
                    };

                    this.smoothedCache[cacheKey] = result;
                    return result;
                }
            }

            return this.generateRawChartData(block, now, currentMoisture, currentVolume, hasRealData);
        },

        generateRawChartData(block, now, currentMoisture, currentVolume, hasRealData) {
            let labels = [];
            let kelembabanData = [];
            let penggunaanAirData = [];

            if (this.currentPeriod === 'today') {
                const currentHour = now.getHours();

                for (let i = 0; i <= currentHour; i += 2) {
                    const hour = i.toString().padStart(2, '0') + ':00';
                    labels.push(hour);

                    if (hasRealData) {
                        kelembabanData.push(currentMoisture);
                        penggunaanAirData.push(currentVolume);
                    } else {
                        kelembabanData.push(null);
                        penggunaanAirData.push(null);
                    }
                }
            } else if (this.currentPeriod === '7days') {
                const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                for (let i = 6; i >= 0; i--) {
                    const date = new Date(now);
                    date.setDate(date.getDate() - i);
                    const dayName = days[date.getDay()];
                    labels.push(dayName);

                    if (i === 0 && hasRealData) {
                        kelembabanData.push(currentMoisture);
                        penggunaanAirData.push(currentVolume);
                    } else {
                        kelembabanData.push(null);
                        penggunaanAirData.push(null);
                    }
                }
            } else if (this.currentPeriod === '30days') {
                for (let i = 3; i >= 0; i--) {
                    const weekLabel = 'Minggu ' + (4 - i);
                    labels.push(weekLabel);

                    if (i === 0 && hasRealData) {
                        kelembabanData.push(currentMoisture);
                        penggunaanAirData.push(currentVolume);
                    } else {
                        kelembabanData.push(null);
                        penggunaanAirData.push(null);
                    }
                }
            }

            const cacheKey = this.getCacheKey(block.name, false);
            const result = {
                kelembaban: labels.map((waktu, idx) => ({
                    waktu: waktu,
                    nilai: kelembabanData[idx],
                })),
                penggunaanAir: labels.map((waktu, idx) => ({
                    waktu: waktu,
                    nilai: penggunaanAirData[idx],
                })),
            };

            this.smoothedCache[cacheKey] = result;
            return result;
        },

        async initializeCharts() {
            if (this.chartsInitializing || this.bloks.length === 0) {
                return;
            }
            this.chartsInitializing = true;

            try {
                const chartLoaderPath = window.__CHART_LOADER_PATH__;
                await import(chartLoaderPath);

                const chartModule = window.ChartModule;

                if (chartModule && typeof chartModule.destroyAllCharts === 'function') {
                    chartModule.destroyAllCharts(this.bloks);
                }

                await new Promise((resolve) => setTimeout(resolve, 100));

                if (chartModule && typeof chartModule.initializeCharts === 'function') {
                    chartModule.initializeCharts(this.bloks);
                    this.chartsLoaded = true;
                }
            } catch (error) {
                console.error('❌ Failed to load charts:', error);
            } finally {
                this.chartsInitializing = false;
            }
        },

        destroyAllCharts() {
            try {
                const chartModule = window.ChartModule;
                if (chartModule && typeof chartModule.destroyAllCharts === 'function') {
                    chartModule.destroyAllCharts(this.bloks);
                    this.chartInstances = {};
                    this.chartsLoaded = false;
                    return;
                }

                if (typeof window.Chart === 'undefined') {
                    this.chartInstances = {};
                    this.chartsLoaded = false;
                    return;
                }

                if (this.bloks && this.bloks.length > 0) {
                    this.bloks.forEach((blok) => {
                        ['chartKelembaban' + blok.id, 'chartPenggunaanAir' + blok.id].forEach((canvasId) => {
                            const canvas = document.getElementById(canvasId);
                            if (canvas) {
                                try {
                                    const chart = window.Chart.getChart(canvas);
                                    if (chart) {
                                        chart.destroy();
                                    }
                                } catch (e) {
                                    // ignore
                                }
                            }
                        });
                    });
                }
            } catch (error) {
                console.error('⚠️ Error destroying charts:', error);
            }

            this.chartInstances = {};
            this.chartsLoaded = false;
        },

        // ========================================
        // SMOOTHING FUNCTIONS
        // ========================================

        async toggleSmoothing() {
            if (!this.useSmoothing && this.noNumericHistory) {
                alert('Tidak ada data history numerik untuk mode Smoothing. Silakan gunakan mode Data Mentah.');
                return;
            }
            this.isSmoothingLoading = true;

            try {
                this.useSmoothing = !this.useSmoothing;
                this.smoothedCache = {};
                this.chartsLoaded = false;
                this.chartsInitializing = false;

                await new Promise((resolve) => setTimeout(resolve, 50));
                this.processStatisticsData();
            } catch (error) {
                console.error('Error toggling smoothing:', error);
            } finally {
                this.isSmoothingLoading = false;
            }
        },

        smoothMovingAverage(arr, win = 5) {
            const out = new Array(arr.length).fill(null);
            const sanitized = arr.map((v) => (v === undefined || v === null || isNaN(v) ? null : parseFloat(v)));

            for (let i = 0; i < sanitized.length; i++) {
                const start = Math.max(0, i - win + 1);
                const slice = sanitized.slice(start, i + 1).filter((v) => v !== null);
                if (slice.length === 0) continue;
                out[i] = slice.reduce((a, b) => a + b, 0) / slice.length;
            }
            return out;
        },

        aggregateByPeriod(rawLogs, period) {
            const now = new Date();
            const result = { labels: [], moistureValues: [], volumeValues: [] };
            if (!rawLogs || rawLogs.length === 0) {
                return result;
            }

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

            const filteredLogs = rawLogs.filter((log) => log.ts >= startDate.getTime());

            if (period === 'today') {
                const hourlyBuckets = {};
                for (let h = 0; h <= now.getHours(); h += 2) {
                    hourlyBuckets[h] = { moisture: [], volume: [] };
                }

                filteredLogs.forEach((log) => {
                    const logDate = new Date(log.ts);
                    const hour = Math.floor(logDate.getHours() / 2) * 2;
                    if (hourlyBuckets[hour]) {
                        if (log.moisture !== null) hourlyBuckets[hour].moisture.push(log.moisture);
                        if (log.volume !== null) hourlyBuckets[hour].volume.push(log.volume);
                    }
                });

                Object.keys(hourlyBuckets)
                    .sort((a, b) => a - b)
                    .forEach((hour) => {
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
                const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                const dailyBuckets = {};

                for (let i = 6; i >= 0; i--) {
                    const date = new Date(now);
                    date.setDate(date.getDate() - i);
                    const dateKey = date.toISOString().split('T')[0];
                    dailyBuckets[dateKey] = {
                        dayName: days[date.getDay()],
                        moisture: [],
                        volume: [],
                    };
                }

                filteredLogs.forEach((log) => {
                    const logDate = new Date(log.ts);
                    const dateKey = logDate.toISOString().split('T')[0];
                    if (dailyBuckets[dateKey]) {
                        if (log.moisture !== null) dailyBuckets[dateKey].moisture.push(log.moisture);
                        if (log.volume !== null) dailyBuckets[dateKey].volume.push(log.volume);
                    }
                });

                Object.keys(dailyBuckets)
                    .sort()
                    .forEach((dateKey) => {
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
                const weeklyBuckets = {};
                for (let w = 3; w >= 0; w--) {
                    weeklyBuckets[w] = { moisture: [], volume: [] };
                }

                filteredLogs.forEach((log) => {
                    const logDate = new Date(log.ts);
                    const daysDiff = Math.floor((now.getTime() - logDate.getTime()) / (1000 * 60 * 60 * 24));
                    const weekIndex = Math.min(3, Math.floor(daysDiff / 7));
                    if (weeklyBuckets[weekIndex]) {
                        if (log.moisture !== null) weeklyBuckets[weekIndex].moisture.push(log.moisture);
                        if (log.volume !== null) weeklyBuckets[weekIndex].volume.push(log.volume);
                    }
                });

                [3, 2, 1, 0].forEach((w) => {
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
                maximumFractionDigits: decimals,
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
        },
    };
};
