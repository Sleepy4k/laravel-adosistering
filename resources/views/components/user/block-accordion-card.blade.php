@props([
    'blockId' => '1',
    'blockName' => 'Blok A',
    'avgHumidity' => '47,38%',
    'avgFlowRate' => '38,57 Liter / Menit',
    'totalVolume' => '78 Liter',
    'sprayers' => []
])

<div class="bg-white rounded-2xl border border-[#C2C2C2] overflow-hidden mb-6" 
     x-data="{ 
        isOpen: true,
        blockId: '{{ $blockId }}'
     }">
    
    <!-- Header Section -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-[#4F4F4F]">{{ $blockName }}</h2>
            <div class="flex items-center gap-3">
                <button 
                    @click="$dispatch('turn-on-all-sprayers-' + blockId)"
                    class="btn-3d-green px-4 py-2 text-sm">
                    Nyalakan Semua
                </button>
                <button 
                    @click="$dispatch('turn-off-all-sprayers-' + blockId)"
                    class="btn-3d-red px-4 py-2 text-sm">
                    Matikan Semua
                </button>
                <button 
                    @click="isOpen = !isOpen"
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600 transition-transform duration-200" 
                         :class="isOpen ? 'rotate-180' : ''" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards (Always Visible) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-4 bg-gray-50">
        <!-- Kelembaban Tanah Rata-Rata -->
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Kelembaban Tanah Rata-Rata</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-[#4F4F4F]">{{ $avgHumidity }}</p>
                @php
                    $humidityValue = (float) str_replace(['%', ','], ['', '.'], $avgHumidity);
                @endphp
                @if($humidityValue >= 60)
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#D4F4DD] text-[#186D3C]">Lembab</span>
                @else
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#FDF1B9] text-[#947E11]">Kering</span>
                @endif
            </div>
        </div>

        <!-- Debit Rata-Rata -->
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Debit Rata-Rata</p>
            <p class="text-2xl font-bold text-[#4F4F4F]">{{ $avgFlowRate }}</p>
        </div>

        <!-- Total Volume Air -->
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Volume Air</p>
            <p class="text-2xl font-bold text-[#4F4F4F]">{{ $totalVolume }}</p>
        </div>
    </div>

    <!-- Sprayers List (Collapsible) -->
    <div x-show="isOpen" 
         x-transition:enter="transition-all duration-300 ease-out"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-[2000px]"
         x-transition:leave="transition-all duration-200 ease-in"
         x-transition:leave-start="opacity-100 max-h-[2000px]"
         x-transition:leave-end="opacity-0 max-h-0"
         class="overflow-hidden">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 py-6">
            @foreach($sprayers as $sprayer)
                <x-user.sprayer-card 
                    :sprayerId="$sprayer['id']"
                    :blockId="$blockId"
                    :name="$sprayer['name']"
                    :location="$sprayer['location']"
                    :sensorStatus="$sprayer['sensorStatus']"
                    :humidity="$sprayer['humidity']"
                    :flowRate="$sprayer['flowRate']"
                    :volume="$sprayer['volume']"
                    :pumpStatus="$sprayer['pumpStatus']"
                    :lastUpdate="$sprayer['lastUpdate']"
                    :isPumpOn="$sprayer['isPumpOn']"
                    :isAutoIrrigation="$sprayer['isAutoIrrigation']"
                />
            @endforeach
        </div>
    </div>
</div>
