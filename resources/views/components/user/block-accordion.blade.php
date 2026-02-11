{{--
    Block Accordion Card — Alpine-aware (no server props)
    
    Must be used INSIDE an x-data scope that provides:
      - block.name                (string)
      - block.avgMoisture         (number)
      - block.avgFlowRate         (number)
      - block.totalVolume         (number)
      - block.isOpen              (bool)
      - block.sprayers[]          (array of sprayer objects)
    
    Parent scope must also provide these methods:
      - turnOnAllSprayers(blockName)
      - turnOffAllSprayers(blockName)
      - toggleRelay(blockName, sprayerName, turnOn)
      - toggleAutoIrrigation(blockName, sprayerName, turnOn)
      - isSprayerLoading(blockName, sprayerName)
      - formatRelativeTime(epochTimestamp)
--}}

<div class="bg-white rounded-2xl border border-[#C2C2C2] overflow-hidden mb-6">
    {{-- Block Header --}}
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-bold text-[#4F4F4F]" x-text="block.name"></h2>
            </div>
            <div class="flex items-center gap-3">
                <button @click="turnOnAllSprayers(block.name)" class="btn-3d-green px-4 py-2 text-sm">Nyalakan Semua</button>
                <button @click="turnOffAllSprayers(block.name)" class="btn-3d-red px-4 py-2 text-sm">Matikan Semua</button>
                <button @click="block.isOpen = !block.isOpen" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600 transition-transform duration-200" :class="block.isOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Block Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-4 bg-gray-50">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Kelembaban Tanah Rata-Rata</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-[#4F4F4F]" x-text="block.avgMoisture.toFixed(2) + '%'"></p>
                <span class="px-2 py-1 text-xs font-medium rounded-full" 
                      :class="block.avgMoisture >= 60 ? 'bg-[#D4F4DD] text-[#186D3C]' : 'bg-[#FDF1B9] text-[#947E11]'"
                      x-text="block.avgMoisture >= 60 ? 'Lembab' : 'Kering'"></span>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Debit Rata-Rata</p>
            <p class="text-2xl font-bold text-[#4F4F4F]" x-text="block.avgFlowRate.toFixed(2) + ' L/min'"></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Volume Air</p>
            <p class="text-2xl font-bold text-[#4F4F4F]" x-text="block.totalVolume.toFixed(2) + ' L'"></p>
        </div>
    </div>

    {{-- Sprayers List (Collapsible) --}}
    <div x-show="block.isOpen" 
         x-transition:enter="transition-all duration-300 ease-out"
         x-transition:enter-start="opacity-0 max-h-0"
     x-transition:enter-end="opacity-100 max-h-[2000px]"
     x-transition:leave="transition-all duration-200 ease-in"
     x-transition:leave-start="opacity-100 max-h-[2000px]"
     x-transition:leave-end="opacity-0 max-h-0"
     class="overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 py-6">
        <template x-for="(sprayer, sprayerIdx) in block.sprayers" :key="sprayer.name">
            @include('components.user.sprayer-card')
        </template>
    </div>
</div>
</div>