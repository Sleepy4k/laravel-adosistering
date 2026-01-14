@props([
    'sprayerId' => '1',
    'blockId' => '1',
    'blockName' => 'Block A',
    'name' => 'Sprayer 1',
    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
    'sensorStatus' => 'Terhubung',
    'humidity' => '47,38%',
    'flowRate' => '33 L / Menit',
    'volume' => '78 Liter',
    'pumpStatus' => 'Aktif',
    'lastUpdate' => '5 menit yang lalu',
    'isPumpOn' => true,
    'isAutoIrrigation' => false
])

<div class="bg-white rounded-xl border border-gray-200 p-5" 
     x-data="sprayerCard({
        sprayerId: '{{ $sprayerId }}',
        blockId: '{{ $blockId }}',
        blockName: '{{ $blockName }}',
        sprayerName: '{{ $name }}',
        isPumpOn: {{ $isPumpOn ? 'true' : 'false' }},
        isAutoIrrigation: {{ $isAutoIrrigation ? 'true' : 'false' }},
        sensorStatus: '{{ $sensorStatus }}',
        humidity: '{{ $humidity }}',
        flowRate: '{{ $flowRate }}',
        volume: '{{ $volume }}',
        pumpStatus: '{{ $pumpStatus }}',
        lastUpdate: '{{ $lastUpdate }}'
     })">
    
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-[#4F4F4F] mb-1">{{ $name }}</h3>
            <p class="text-xs text-gray-500 line-clamp-2">{{ $location }}</p>
        </div>
        <!-- Connection indicator -->
        <div class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full" 
                  :class="firebaseConnected ? 'bg-green-500' : 'bg-red-500'"></span>
            <span class="text-xs text-gray-400" x-text="firebaseConnected ? 'Live' : 'Offline'"></span>
        </div>
    </div>

    <!-- Sensor Status -->
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
        <span class="text-sm font-medium text-gray-700">Sensor Data</span>
        <span class="px-3 py-1 text-xs font-medium rounded-full"
              :class="sensorStatus === 'online' ? 'bg-[#D4F4DD] text-[#186D3C]' : 'bg-gray-200 text-gray-700'"
              x-text="sensorStatus === 'online' ? 'online' : sensorStatus"></span>
    </div>

    <!-- Sensor Data or Error Message -->
    <template x-if="sensorStatus === 'Gangguan Sensor'">
        <!-- Error Message -->
        <div class="mb-4">
            <p class="text-sm text-red-600 italic">*Sensor Pompa mengalami masalah</p>
        </div>
    </template>
    
    <template x-if="sensorStatus !== 'Gangguan Sensor'">
        <!-- Sensor Readings -->
        <div class="space-y-3 mb-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Kelembaban Tanah</span>
                <span class="text-sm font-semibold text-[#4F4F4F]" x-text="humidity"></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Debit Air Rata-Rata</span>
                <span class="text-sm font-semibold text-[#4F4F4F]" x-text="flowRate"></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Total Volume Air</span>
                <span class="text-sm font-semibold text-[#4F4F4F]" x-text="volume"></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Pompa</span>
                <span class="text-sm font-semibold" 
                      :class="isPumpOn ? 'text-[#186D3C]' : 'text-gray-500'"
                      x-text="pumpStatus"></span>
            </div>
        </div>
    </template>

    <!-- Last Update -->
    <p class="text-xs text-gray-400 mb-4 text-center" x-text="'Terakhir update ' + lastUpdate">sss</p>

    <!-- Controls -->
    <div class="space-y-3 pt-4 border-t border-gray-200">
        <!-- Pump Toggle -->
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">
                <span x-text="isPumpOn ? 'ON' : 'OFF'" class="font-bold"></span>
            </span>
            <button type="button" 
                    @click="togglePump()"
                    :disabled="isAutoIrrigation || isLoading"
                    class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors"
                    :class="[
                        isPumpOn ? 'bg-[#67B744]' : 'bg-gray-300',
                        (isAutoIrrigation || isLoading) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                    ]">
                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform shadow-md"
                      :class="isPumpOn ? 'translate-x-6' : 'translate-x-1'"></span>
                <!-- Loading indicator -->
                <span x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>

        <!-- Auto Irrigation Toggle -->
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">Irigasi Otomatis</span>
            <button type="button" 
                    @click="toggleAutoIrrigation()"
                    class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors cursor-pointer"
                    :class="isAutoIrrigation ? 'bg-[#67B744]' : 'bg-gray-300'">
                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform shadow-md"
                      :class="isAutoIrrigation ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
        </div>
    </div>
</div>

<script>
function sprayerCard(config) {
    return {
        // Config
        sprayerId: config.sprayerId,
        blockId: config.blockId,
        blockName: config.blockName,
        sprayerName: config.sprayerName,
        
        // State
        isPumpOn: config.isPumpOn,
        isAutoIrrigation: config.isAutoIrrigation,
        sensorStatus: config.sensorStatus,
        humidity: config.humidity,
        flowRate: config.flowRate,
        volume: config.volume,
        pumpStatus: config.pumpStatus,
        lastUpdate: config.lastUpdate,
        
        // Firebase state
        firebaseConnected: false,
        isLoading: false,
        unsubscribe: null,
        
        init() {
            console.log(`🔌 Initializing Sprayer Card: ${this.blockName}/${this.sprayerName}`);
            
            // Listen for turn on/off all sprayers in this block
            window.addEventListener('turn-on-all-sprayers-' + this.blockId, () => {
                this.setPumpState(true);
            });
            
            window.addEventListener('turn-off-all-sprayers-' + this.blockId, () => {
                this.setPumpState(false);
                this.isAutoIrrigation = false;
            });
            
            // Connect to Firebase after a short delay to ensure FirebaseIoT is loaded
            setTimeout(() => this.connectToFirebase(), 500);
        },
        
        connectToFirebase() {
            if (typeof window.FirebaseIoT === 'undefined') {
                console.warn('⏳ Firebase not ready, retrying...');
                setTimeout(() => this.connectToFirebase(), 1000);
                return;
            }
            
            // Listen to relay status from Firebase
            // Path: MAOS/{blockName}/{sprayerName}/control/relay
            this.unsubscribe = window.FirebaseIoT.listenToData(
                `MAOS/${this.blockName}/${this.sprayerName}/control/relay`,
                (relayValue) => {
                    this.firebaseConnected = true;
                    console.log(`📡 [${this.sprayerName}] Relay status from Firebase:`, relayValue);
                    
                    if (relayValue !== null) {
                        // Firebase uses string "ON" / "OFF"
                        this.isPumpOn = relayValue === "ON";
                        this.pumpStatus = this.isPumpOn ? 'Aktif' : 'Mati';
                    }
                }
            );
            
            // Also listen to sensor data
            // Firebase path: MAOS/{blockName}/{sprayerName}/data
            // Fields: moisture, flowRate, totalVolume, arah_angin, kecepatan_kmh, kecepatan_mps, timestamp
            window.FirebaseIoT.listenToData(
                `MAOS/${this.blockName}/${this.sprayerName}/data`,
                (data) => {
                    if (data) {
                        console.log(`📊 [${this.sprayerName}] Sensor data:`, data);
                        this.firebaseConnected = true;
                        
                        // Map Firebase fields to UI
                        if (data.moisture !== undefined) {
                            this.humidity = data.moisture + '%';
                        }
                        if (data.flowRate !== undefined) {
                            this.flowRate = data.flowRate + ' L/min';
                        }
                        if (data.totalVolume !== undefined) {
                            this.volume = data.totalVolume + ' L';
                        }
                        
                        // Update last update time
                        if (data.timestamp && data.timestamp > 0) {
                            this.lastUpdate = new Date(data.timestamp * 1000).toLocaleString('id-ID');
                        } else {
                            this.lastUpdate = new Date().toLocaleString('id-ID');
                        }
                        this.sensorStatus = 'online';
                    }
                }
            );
        },
        
        async togglePump() {
            if (this.isLoading || this.isAutoIrrigation) return;
            
            const newState = !this.isPumpOn;
            console.log(`🔄 [${this.sprayerName}] Toggling pump to:`, newState ? 'ON' : 'OFF');
            
            this.isLoading = true;
            
            try {
                // Send to Firebase: MAOS/{blockName}/{sprayerName}/control/relay
                const success = await window.FirebaseIoT.controlRelay(
                    this.blockName,
                    this.sprayerName,
                    newState
                );
                
                if (success) {
                    console.log(`✅ [${this.sprayerName}] Relay updated successfully`);
                    // State will be updated via listener
                } else {
                    console.error(`❌ [${this.sprayerName}] Failed to update relay`);
                    alert('Gagal mengubah status pompa. Coba lagi.');
                }
            } catch (error) {
                console.error(`❌ [${this.sprayerName}] Error:`, error);
                alert('Error: ' + error.message);
            } finally {
                this.isLoading = false;
            }
        },
        
        async setPumpState(isOn) {
            if (this.isLoading) return;
            
            console.log(`🔄 [${this.sprayerName}] Setting pump to:`, isOn ? 'ON' : 'OFF');
            
            this.isLoading = true;
            
            try {
                await window.FirebaseIoT.controlRelay(
                    this.blockName,
                    this.sprayerName,
                    isOn
                );
            } catch (error) {
                console.error(`❌ [${this.sprayerName}] Error:`, error);
            } finally {
                this.isLoading = false;
            }
        },
        
        toggleAutoIrrigation() {
            this.isAutoIrrigation = !this.isAutoIrrigation;
            console.log(`🤖 [${this.sprayerName}] Auto irrigation:`, this.isAutoIrrigation ? 'ON' : 'OFF');
        },
        
        destroy() {
            // Cleanup listener when component is destroyed
            if (this.unsubscribe) {
                this.unsubscribe();
            }
        }
    }
}
</script>
