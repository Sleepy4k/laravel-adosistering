/**
 * Irrigation History Alpine.js Component
 * Extracted from inline script in irrigation-history.blade.php
 *
 * Usage in Blade: x-data="irrigationHistory()"
 * Requires: window.__FIREBASE_MODULE_PATH__ to be set before this runs.
 * Requires: window.__FIREBASE_CONFIG__ to be set (from Laravel config).
 */

window.irrigationHistory = function () {
    return {
        // Store cards data from Firebase
        cards: [],

        // Available blocks for filter dropdown (dari Firebase)
        availableBlocks: [],

        // Loading state
        isLoading: true,

        // Current date display
        currentDate: '',

        // Filter states
        namaLahan: '',
        statusIrigasi: '',
        jenisIrigasi: '',
        tanggalFilter: '',

        // Dropdown open states
        dropdownNamaLahan: false,
        dropdownStatusIrigasi: false,
        dropdownJenisIrigasi: false,

        // Computed filtered cards
        get filteredCards() {
            return this.cards.filter((card) => {
                const matchNamaLahan = this.namaLahan === '' || card.blok === this.namaLahan;
                const matchStatusIrigasi = this.statusIrigasi === '' || card.status_irigasi === this.statusIrigasi;
                // jenisIrigasi filter not functional yet - data not in database
                const matchTanggal = this.tanggalFilter === '' || card.tanggal === this.tanggalFilter;
                return matchNamaLahan && matchStatusIrigasi && matchTanggal;
            });
        },

        // Initialize component
        async init() {
            // Set current date
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
            ];
            const now = new Date();
            this.currentDate =
                days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

            // Load history from Firebase using Vite module
            await this.loadHistoryFromFirebase();
        },

        // Load history data from Firebase using the Vite Firebase module
        async loadHistoryFromFirebase() {
            try {
                const firebaseModulePath = window.__FIREBASE_MODULE_PATH__;
                const config = window.__FIREBASE_CONFIG__;

                if (!config || !config.api_key) {
                    console.error('Firebase config not found');
                    this.isLoading = false;
                    return;
                }

                // Import the module (sets window.FirebaseModule)
                await import(firebaseModulePath);

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
                    appId: config.app_id,
                };

                const db = firebaseModule.initializeFirebase(firebaseConfig);

                if (!db) {
                    console.error('Firebase database initialization failed');
                    this.isLoading = false;
                    return;
                }

                // Get MAOS data
                const maosData = await firebaseModule.getData('MAOS');

                if (!maosData) {
                    this.isLoading = false;
                    return;
                }

                const historyCards = [];
                const blocks = new Set();

                // Loop through each Block in MAOS
                Object.keys(maosData).forEach((blockName) => {
                    const blockData = maosData[blockName];
                    if (!blockData || typeof blockData !== 'object') {
                        return;
                    }

                    // Add block to available blocks for filter
                    blocks.add(blockName);

                    // Loop through each Sprayer in Block
                    Object.keys(blockData).forEach((sprayerName) => {
                        // Skip non-sprayer keys (like "control", "data", "info", etc)
                        if (!sprayerName.includes('Sprayer')) {
                            return;
                        }

                        const sprayerData = blockData[sprayerName];
                        if (!sprayerData || typeof sprayerData !== 'object') {
                            return;
                        }

                        // Check if sprayer has history
                        if (sprayerData.history && typeof sprayerData.history === 'object') {
                            // Loop through each date in history (format: DD-MM-YYYY)
                            Object.keys(sprayerData.history).forEach((dateKey) => {
                                const dateData = sprayerData.history[dateKey];
                                if (!dateData || typeof dateData !== 'object') {
                                    return;
                                }

                                // Loop through each history entry (unique ID like -OkNNb3Axdpd0D0C9EYc)
                                Object.keys(dateData).forEach((entryId) => {
                                    const entry = dateData[entryId];
                                    if (!entry || typeof entry !== 'object') {
                                        return;
                                    }

                                    // Determine status irigasi based on relay or flow data
                                    let statusIrigasi = 'Selesai';
                                    const flowVal = parseFloat(entry.flow_Lmin || '0');
                                    const relayVal = entry.relay;

                                    if (relayVal === 1 || relayVal === '1' || flowVal > 0) {
                                        statusIrigasi = 'Aktif';
                                    } else if (entry.moisture_status === 'Kering' && flowVal === 0) {
                                        statusIrigasi = 'Selesai';
                                    }

                                    // Create card object
                                    historyCards.push({
                                        id: `${blockName}-${sprayerName}-${dateKey}-${entryId}`,
                                        blok: blockName,
                                        sprayer: sprayerName,
                                        tanggal: this.convertDateFormat(dateKey),
                                        tanggalDisplay: dateKey,
                                        status_irigasi: statusIrigasi,
                                        moisture_percent: entry.moisture_percent || '0',
                                        moisture_status: entry.moisture_status || 'Unknown',
                                        totalVolume_L: entry.totalVolume_L || '0.00',
                                        flow_Lmin: entry.flow_Lmin || '0.00',
                                        flow_mLs: entry.flow_mLs || '0.00',
                                        kecepatan_kmh: entry.kecepatan_kmh || '0.00',
                                        kecepatan_mps: entry.kecepatan_mps || '0.00',
                                        arah_angin: entry.arah_angin || '',
                                        timestamp: entry.timestamp || dateKey,
                                    });
                                });
                            });
                        }
                    });
                });

                // Sort by timestamp (newest first)
                historyCards.sort((a, b) => {
                    return this.parseTimestamp(b.timestamp) - this.parseTimestamp(a.timestamp);
                });

                this.cards = historyCards;
                this.availableBlocks = Array.from(blocks).sort();
                this.isLoading = false;
            } catch (error) {
                console.error('Failed to load irrigation history:', error);
                this.isLoading = false;
            }
        },

        // Convert date from DD-MM-YYYY to YYYY-MM-DD for filter comparison
        convertDateFormat(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        },

        // Parse timestamp string to Date object for sorting
        parseTimestamp(timestampStr) {
            if (!timestampStr) return 0;
            // Format: "DD-MM-YYYY HH:MM:SS"
            const parts = timestampStr.split(' ');
            if (parts.length < 1) return 0;

            const dateParts = parts[0].split('-');
            if (dateParts.length !== 3) return 0;

            const timeParts = parts[1] ? parts[1].split(':') : ['00', '00', '00'];

            const year = parseInt(dateParts[2]);
            const month = parseInt(dateParts[1]) - 1;
            const day = parseInt(dateParts[0]);
            const hour = parseInt(timeParts[0]) || 0;
            const minute = parseInt(timeParts[1]) || 0;
            const second = parseInt(timeParts[2]) || 0;

            return new Date(year, month, day, hour, minute, second).getTime();
        },

        // Format timestamp for display - Format: "27 Des 2025, 11:05 WIB"
        formatTimestamp(timestampStr) {
            if (!timestampStr) return '-';

            // Parse timestamp string (format: "DD-MM-YYYY HH:MM:SS")
            const parts = timestampStr.split(' ');
            if (parts.length < 2) return timestampStr;

            const dateParts = parts[0].split('-');
            const timeParts = parts[1].split(':');

            if (dateParts.length !== 3 || timeParts.length < 2) return timestampStr;

            const day = parseInt(dateParts[0]);
            const monthIndex = parseInt(dateParts[1]) - 1;
            const year = parseInt(dateParts[2]);
            const hour = timeParts[0];
            const minute = timeParts[1];

            // Month names in Indonesian (short form)
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const monthName = monthNames[monthIndex];

            // Format: "27 Des 2025, 11:05 WIB"
            return `${day} ${monthName} ${year}, ${hour}:${minute} WIB`;
        },

        // Reset all filters
        reset() {
            this.namaLahan = '';
            this.statusIrigasi = '';
            this.jenisIrigasi = '';
            this.tanggalFilter = '';
        },
    };
};
