{{--
    Sprayer Card — Alpine-aware (no server props)
    
    Must be used INSIDE an x-for loop that provides a `sprayer` object with:
      - sprayer.name                (string)
      - sprayer.moisture            (number)
      - sprayer.flowRate            (number)
      - sprayer.totalVolume         (number)
      - sprayer.relay               ("ON" | "OFF")
      - sprayer.autoIrrigation      (bool)
      - sprayer.sensorConnected     (bool)
      - sprayer.timestamp           (epoch number)
      - sprayer.batasKering         (number)
      - sprayer.batasBasah          (number)
    
    Parent scope (block-accordion or iotDashboard) must provide:
      - block.name
      - toggleRelay(blockName, sprayerName, turnOn)
      - toggleAutoIrrigation(blockName, sprayerName, turnOn)
      - isSprayerLoading(blockName, sprayerName)
      - formatRelativeTime(epochTimestamp)
--}}

<div class="bg-blue rounded-xl border border-gray-200 p-5">
    {{-- Sprayer Header --}}
    <h3 class="text-lg font-bold text-[#4F8936] mb-2" x-text="sprayer.name"></h3>
    
    <p class="text-xs text-[#4F4F4F] mb-4">Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah</p>

    {{-- Sensor Status --}}
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-[#C2C2C2]">
        <span class="text-sm font-medium text-[#4F4F4F]">Sensor Data</span>
        <span class="px-3 py-1 text-xs font-medium rounded-full"
              :class="sprayer.sensorConnected !== false ? 'bg-[#D4F4DD] text-[#186D3C]' : 'bg-[#FDF1B9] text-[#947E11]'"
              x-text="sprayer.sensorConnected !== false ? 'Terhubung' : 'Gangguan Sensor'"></span>
    </div>

    {{-- Error Message (if sensor has issue) --}}
    <div x-show="sprayer.sensorConnected === false" class="mb-4">
        <p class="text-xs text-[#C71A34] italic">*Sensor Pompa mengalami masalah</p>
    </div>

    {{-- Sensor Readings --}}
    <div class="space-y-3 mb-4 pt-1">
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#4F4F4F]">Kelembaban Tanah</span>
            <span class="text-sm font-semibold text-[#186D3C]" x-text="sprayer.moisture + '%'"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#4F4F4F]">Debit Air Rata-Rata</span>
            <span class="text-sm font-semibold text-[#186D3C]" x-text="sprayer.flowRate + ' L / Menit'"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#4F4F4F]">Total Volume Air</span>
            <span class="text-sm font-semibold text-[#186D3C]" x-text="sprayer.totalVolume + ' Liter'"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#4F4F4F]">Pompa</span>
            <span class="px-3 py-1 text-xs font-medium rounded-full"
                  :class="sprayer.relay === 'ON' ? 'bg-[#D4F4DD] text-[#186D3C]' : 'bg-[#ECECEC] text-[#4F4F4F]'"
                  x-text="sprayer.relay === 'ON' ? 'Aktif' : 'Mati'"></span>
        </div>
    </div>

    {{-- Last Update (Float Right) --}}
    <div class="mb-4 text-right">
        <p class="text-xs text-[#4F4F4F] italic" x-text="'Terakhir update ' + formatRelativeTime(sprayer.timestamp)"></p>
    </div>

    {{-- Controls - Toggle and Checkbox in one row --}}
    <div class="flex items-center justify-between pt-4 border-t border-[#C2C2C2]">
        {{-- Toggle ON/OFF (Left Side) --}}
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-[#4F4F4F]" x-text="sprayer.relay === 'ON' ? 'ON' : 'OFF'"></span>
            <button type="button" 
                    @click="toggleRelay(block.name, sprayer.name, sprayer.relay !== 'ON')"
                    :disabled="isSprayerLoading(block.name, sprayer.name)"
                    class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors"
                    :class="[
                        sprayer.relay === 'ON' ? 'bg-[#67B744]' : 'bg-gray-300',
                        isSprayerLoading(block.name, sprayer.name) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                    ]">
                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform shadow-md"
                      :class="sprayer.relay === 'ON' ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
        </div>
        
        {{-- Irigasi Otomatis Toggle (Right Side) --}}
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-[#4F4F4F]">Irigasi Otomatis</span>
            <button type="button" 
                    @click="toggleAutoIrrigation(block.name, sprayer.name, !sprayer.autoIrrigation)"
                    :disabled="isSprayerLoading(block.name, sprayer.name)"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                    :class="[
                        sprayer.autoIrrigation ? 'bg-[#67B744]' : 'bg-gray-300',
                        isSprayerLoading(block.name, sprayer.name) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                    ]">
                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-md"
                      :class="sprayer.autoIrrigation ? 'translate-x-6' : 'translate-x-1'"></span>
            </button>
        </div>
    </div>

    {{-- Threshold Info (shown when auto irrigation is ON) --}}
    <div x-show="sprayer.autoIrrigation" class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
        <p class="text-xs text-green-700">
            <span class="font-semibold">Batas Otomatis:</span> 
            Pompa ON saat kelembaban &lt; <span x-text="sprayer.batasKering" class="font-bold"></span>%, 
            OFF saat &gt; <span x-text="sprayer.batasBasah" class="font-bold"></span>%
        </p>
    </div>
</div>
