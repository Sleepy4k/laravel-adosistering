{{-- Firebase IoT Dashboard Component --}}
@props(['firebase' => null])

@if($firebase)
<script>
    /**
     * Alpine.js component for IoT Dashboard with Firebase Realtime
     */
    function firebaseIotDashboard(config) {
        return {
            firebase: null,
            isConnected: false,
            sensors: {},
            
            init() {
                // Initialize Firebase when component loads
                if (window.FirebaseIoT && config) {
                    this.firebase = window.FirebaseIoT.initialize(config);
                    if (this.firebase) {
                        this.isConnected = true;
                        console.log('Firebase IoT Dashboard: Connected');
                    }
                }
            },

            /**
             * Listen to sensor updates for a specific sprayer
             */
            listenToSensor(sprayerId, callback) {
                if (!window.FirebaseIoT) return;
                
                window.FirebaseIoT.listenToSensor(sprayerId, (data) => {
                    if (data) {
                        this.sensors[sprayerId] = data;
                        if (callback) callback(data);
                    }
                });
            },

            /**
             * Control pump on/off
             */
            async togglePump(sprayerId, isOn) {
                if (!window.FirebaseIoT) return false;
                
                const success = await window.FirebaseIoT.controlPump(sprayerId, isOn);
                if (success) {
                    console.log(`Pump ${sprayerId}: ${isOn ? 'ON' : 'OFF'}`);
                }
                return success;
            },

            /**
             * Toggle auto irrigation
             */
            async toggleAutoIrrigation(sprayerId, isAuto) {
                if (!window.FirebaseIoT) return false;
                
                const success = await window.FirebaseIoT.setAutoIrrigation(sprayerId, isAuto);
                if (success) {
                    console.log(`Auto Irrigation ${sprayerId}: ${isAuto ? 'ON' : 'OFF'}`);
                }
                return success;
            },

            /**
             * Get sensor data
             */
            getSensorData(sprayerId) {
                return this.sensors[sprayerId] || null;
            }
        }
    }

    // Make available globally
    window.firebaseIotDashboard = firebaseIotDashboard;
</script>
@endif
