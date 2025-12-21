@props([
    'sprayerId' => '1',
    'blockId' => '1',
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
     x-data="{
        sprayerId: '{{ $sprayerId }}',
        blockId: '{{ $blockId }}',
        isPumpOn: {{ $isPumpOn ? 'true' : 'false' }},
        isAutoIrrigation: {{ $isAutoIrrigation ? 'true' : 'false' }},
        sensorStatus: '{{ $sensorStatus }}',
        humidity: '{{ $humidity }}',
        flowRate: '{{ $flowRate }}',
        volume: '{{ $volume }}',
        pumpStatus: '{{ $pumpStatus }}',
        lastUpdate: '{{ $lastUpdate }}',
        
        init() {
            // Listen for turn on/off all sprayers in this block
            window.addEventListener('turn-on-all-sprayers-' + this.blockId, () => {
                this.isPumpOn = true;
                this.updatePumpStatus();
            });
            
            window.addEventListener('turn-off-all-sprayers-' + this.blockId, () => {
                this.isPumpOn = false;
                this.isAutoIrrigation = false;
                this.updatePumpStatus();
            });
        },
        
        togglePump() {
            this.isPumpOn = !this.isPumpOn;
            this.updatePumpStatus();
        },
        
        toggleAutoIrrigation() {
            this.isAutoIrrigation = !this.isAutoIrrigation;
            if (this.isAutoIrrigation) {
                // Auto irrigation enabled, pump control by system
                console.log('Auto irrigation enabled for sprayer', this.sprayerId);
            }
        },
        
        updatePumpStatus() {
            this.pumpStatus = this.isPumpOn ? 'Aktif' : 'Mati';
            console.log('Pump', this.sprayerId, 'status:', this.pumpStatus);
        }
     }">
    
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-[#4F4F4F] mb-1">{{ $name }}</h3>
            <p class="text-xs text-gray-500 line-clamp-2">{{ $location }}</p>
        </div>
    </div>

    <!-- Sensor Status -->
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
        <span class="text-sm font-medium text-gray-700">Sensor Data</span>
        @if($sensorStatus === 'Terhubung')
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-[#D4F4DD] text-[#186D3C]">
                Terhubung
            </span>
        @elseif($sensorStatus === 'Gangguan Sensor')
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-[#FEE] text-[#C00]">
                Gangguan Sensor
            </span>
        @else
            <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-700">
                {{ $sensorStatus }}
            </span>
        @endif
    </div>

    <!-- Sensor Data or Error Message -->
    @if($sensorStatus === 'Gangguan Sensor')
        <!-- Error Message -->
        <div class="mb-4">
            <p class="text-sm text-red-600 italic">*Sensor Pompa mengalami masalah</p>
        </div>
    @else
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
    @endif

    <!-- Last Update -->
    <p class="text-xs text-gray-400 mb-4 text-center" x-text="'Terakhir update ' + lastUpdate"></p>

    <!-- Controls -->
    <div class="space-y-3 pt-4 border-t border-gray-200">
        <!-- Pump Toggle -->
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">
                <span x-text="isPumpOn ? 'ON' : 'OFF'" class="font-bold"></span>
            </span>
            <button type="button" 
                    @click="togglePump()"
                    :disabled="isAutoIrrigation"
                    class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors"
                    :class="isPumpOn ? 'bg-[#186D3C]' : 'bg-gray-300'"
                    :style="isAutoIrrigation ? 'opacity: 0.5; cursor: not-allowed;' : ''">
                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform shadow-md"
                      :class="isPumpOn ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
        </div>

        <!-- Auto Irrigation Toggle -->
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">Irigasi Otomatis</span>
            <button type="button" 
                    @click="toggleAutoIrrigation()"
                    class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors"
                    :class="isAutoIrrigation ? 'bg-[#186D3C]' : 'bg-gray-300'">
                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform shadow-md"
                      :class="isAutoIrrigation ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
        </div>
    </div>
</div>
