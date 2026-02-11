/**
 * IoT Dashboard Alpine.js Component
 * Extracted from inline script in home/user.blade.php
 *
 * Usage in Blade: x-data="iotDashboard({ apiKey: '...', ... })"
 * Requires: window.__FIREBASE_MODULE_PATH__ to be set before this runs.
 */

window.iotDashboard = function (firebaseConfig) {
    return {
        isConnected: false,
        isLoading: true,
        firebaseBlocks: [],
        loadingStates: {},
        _firebaseModule: null,

        init() {
            this.loadFirebaseModule(firebaseConfig);
        },

        async loadFirebaseModule(config) {
            try {
                const modulePath = window.__FIREBASE_MODULE_PATH__;

                await import(modulePath);

                const firebaseModule = window.FirebaseModule;

                if (!firebaseModule || typeof firebaseModule.initializeFirebase !== 'function') {
                    console.error('❌ Firebase module not found on window.FirebaseModule');
                    this.isLoading = false;
                    return;
                }

                this._firebaseModule = firebaseModule;

                this.initializeFirebaseWithModule(config, firebaseModule);
            } catch (error) {
                console.error('❌ Failed to load Firebase module:', error);
                this.isLoading = false;
            }
        },

        initializeFirebaseWithModule(config, firebaseModule) {
            if (!firebaseModule) {
                console.error('❌ Firebase module not provided');
                this.isLoading = false;
                return;
            }

            if (config && config.apiKey) {
                try {
                    const db = firebaseModule.initializeFirebase(config);
                    if (db) {
                        window.FirebaseIoT = firebaseModule;
                        this._firebaseModule = firebaseModule;

                        this.isConnected = true;
                        this.listenToBlocks();
                    } else {
                        console.error('❌ Firebase gagal diinisialisasi');
                        this.isLoading = false;
                    }
                } catch (error) {
                    console.error('❌ Firebase initialization error:', error);
                    this.isLoading = false;
                }
            } else {
                console.error('❌ Firebase config tidak ditemukan');
                this.isLoading = false;
            }
        },

        // Backward compatibility
        initializeFirebase(config) {
            this.initializeFirebaseWithModule(config, this._firebaseModule);
        },

        getLoadingKey(blockName, sprayerName) {
            return blockName + '/' + sprayerName;
        },

        isSprayerLoading(blockName, sprayerName) {
            return this.loadingStates[this.getLoadingKey(blockName, sprayerName)] || false;
        },

        setSprayerLoading(blockName, sprayerName, loading) {
            const key = this.getLoadingKey(blockName, sprayerName);
            this.loadingStates[key] = loading;
            this.loadingStates = { ...this.loadingStates };
        },

        listenToBlocks() {
            window.FirebaseIoT.listenToMAOS((result) => {
                this.isLoading = false;

                if (result.blocks && result.blocks.length > 0) {
                    result.blocks.forEach((block) => {
                        const existing = this.firebaseBlocks.find((b) => b.name === block.name);
                        block.isOpen = existing ? existing.isOpen : true;
                    });

                    this.firebaseBlocks = result.blocks;
                }
            });
        },

        async toggleRelay(blockName, sprayerName, turnOn) {
            if (this.isSprayerLoading(blockName, sprayerName)) return;

            this.setSprayerLoading(blockName, sprayerName, true);

            try {
                const success = await window.FirebaseIoT.controlRelay(blockName, sprayerName, turnOn);
                if (!success) {
                    alert('Gagal mengubah status pompa');
                }
            } catch (error) {
                console.error('Error toggle relay:', error);
                alert('Error: ' + error.message);
            } finally {
                setTimeout(() => {
                    this.setSprayerLoading(blockName, sprayerName, false);
                }, 500);
            }
        },

        async turnOnAllSprayers(blockName) {
            const block = this.firebaseBlocks.find((b) => b.name === blockName);
            if (!block) return;

            for (const sprayer of block.sprayers) {
                await this.toggleRelay(blockName, sprayer.name, true);
            }
        },

        async turnOffAllSprayers(blockName) {
            const block = this.firebaseBlocks.find((b) => b.name === blockName);
            if (!block) return;

            for (const sprayer of block.sprayers) {
                await this.toggleRelay(blockName, sprayer.name, false);
            }
        },

        /**
         * Toggle Irigasi Otomatis mode
         * Path: MAOS/{blockName}/{sprayerName}/control/mode
         * Value: 1 = ON (Otomatis), 0 = OFF (Manual)
         */
        async toggleAutoIrrigation(blockName, sprayerName, turnOn) {
            if (this.isSprayerLoading(blockName, sprayerName)) return;

            this.setSprayerLoading(blockName, sprayerName, true);

            try {
                const success = await window.FirebaseIoT.setIrrigationMode(blockName, sprayerName, turnOn);
                if (!success) {
                    alert('Gagal mengubah mode irigasi otomatis');
                }
            } catch (error) {
                console.error('Error toggle auto irrigation:', error);
                alert('Error: ' + error.message);
            } finally {
                setTimeout(() => {
                    this.setSprayerLoading(blockName, sprayerName, false);
                }, 500);
            }
        },

        formatTimestamp(timestamp) {
            if (!timestamp || timestamp === 0) {
                return new Date().toLocaleString('id-ID');
            }
            return new Date(timestamp * 1000).toLocaleString('id-ID');
        },

        formatRelativeTime(epochTimestamp) {
            if (!epochTimestamp || epochTimestamp === 0) {
                return 'Belum ada data';
            }

            const timestamp = Number(epochTimestamp);
            const nowInSeconds = Math.floor(Date.now() / 1000);
            const diffInSeconds = nowInSeconds - timestamp;

            if (diffInSeconds < 0) return 'Baru saja';

            const minutes = Math.floor(diffInSeconds / 60);

            if (minutes < 1) return 'Baru saja';
            if (minutes < 60) return `${minutes} menit yang lalu`;

            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours} jam yang lalu`;

            const days = Math.floor(hours / 24);
            if (days < 30) return `${days} hari yang lalu`;

            const months = Math.floor(days / 30);
            if (months === 1) return '1 bulan yang lalu';

            return `${months} bulan yang lalu`;
        },
    };
};
