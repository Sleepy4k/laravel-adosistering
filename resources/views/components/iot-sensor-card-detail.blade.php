@props([
    'id' => '1',
    'name' => 'Kawista Emji Mernek Jenek',
    'location' => 'Kec. Maos, Kabupaten Cilacap, Jawa Tengah',
    'sensorStatus' => 'Terhubung',
    'humidity' => '47,38%',
    'flowRate' => '33 L / Menit',
    'volume' => '78 Liter',
    'pumpStatus' => 'Aktif',
    'lastUpdate' => '5 menit yang lalu',
    'isPumpOn' => true,
    'isAutoIrrigation' => false,
    'mapCenter' => [-7.5595, 109.0134],
    'mapZoom' => 15
])

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
    crossorigin=""/>
<style>
    .leaflet-container {
        border-radius: 1rem;
    }
</style>
@endpush

<div class="space-y-6">
    {{-- Card Section: Info & Controls --}}
    <div class="bg-white rounded-2xl border border-[#C2C2C2] p-6">
        {{-- Header: Avatar, Name, Location --}}
        <div class="flex items-start gap-3 mb-4">
            <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-2xl font-semibold text-primary-darker mb-0.5">{{ $name }}</p>
                <p class="text-base text-[#808080]">{{ $location }}</p>
            </div>
        </div>

        {{-- Sensor Data Section --}}
        <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-base font-semibold text-[#4F4F4F]">Sensor Data</p>
                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $sensorStatus === 'Terhubung' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-red-50 text-red-700' }}">
                    {{ $sensorStatus }}
                </span>
            </div>

            <div class="border-t border-[#C2C2C2] pt-3">
                {{-- Kelembaban Tanah --}}
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-[#4F4F4F]">Kelembaban Tanah</span>
                    <span class="text-sm font-semibold text-primary-color">{{ $humidity }}</span>
                </div>
                
                {{-- Debit Air Rata-Rata --}}
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-[#4F4F4F]">Debit Air Rata-Rata</span>
                    <span class="text-sm font-semibold text-primary-color">{{ $flowRate }}</span>
                </div>
                
                {{-- Total Volume Air --}}
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-[#4F4F4F]">Total Volume Air</span>
                    <span class="text-sm font-semibold text-primary-color">{{ $volume }}</span>
                </div>
                
                {{-- Pompa --}}
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-[#4F4F4F]">Pompa</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $pumpStatus === 'Aktif' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-red-50 text-red-700' }}">
                        {{ $pumpStatus }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Footer: Last Update, Toggle, Checkbox --}}
        <div class="pt-4 border-t border-[#C2C2C2]">
            <p class="text-xs text-[#999999] italic text-right mb-4">Terakhir update {{ $lastUpdate }}</p>
            
            <div class="flex items-center justify-between">
                {{-- Toggle Switch with ON Label --}}
                <div class="flex items-center gap-2">
                    <button 
                        type="button"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $isPumpOn ? 'bg-primary' : 'bg-gray-300' }}"
                        x-data="{ on: {{ $isPumpOn ? 'true' : 'false' }} }"
                        @click="on = !on"
                        :class="on ? 'bg-primary' : 'bg-gray-300'"
                    >
                        <span 
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                            :class="on ? 'translate-x-6' : 'translate-x-1'"
                        ></span>
                    </button>
                    <span 
                        class="text-sm font-bold"
                        x-data="{ on: {{ $isPumpOn ? 'true' : 'false' }} }"
                        :class="on ? 'text-[#4F4F4F]' : 'text-[#999999]'"
                        x-text="on ? 'ON' : 'OFF'"
                    >{{ $isPumpOn ? 'ON' : 'OFF' }}</span>
                </div>
                
                {{-- Irigasi Otomatis Checkbox --}}
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-[#4F4F4F]">Irigasi Otomatis</span>
                    <input 
                        type="checkbox" 
                        class="checkbox-green"
                        {{ $isAutoIrrigation ? 'checked' : '' }}
                    >
                </label>
            </div>
        </div>
    </div>

    {{-- Map Section: Interactive Map (Separate Card) --}}
    <div class="bg-white p-5 rounded-2xl border border-[#C2C2C2] overflow-hidden">
        <div class="relative">
            <div 
                id="map-region-{{ $id }}" 
                class="w-full h-80 sm:h-120 z-0"
            ></div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Leaflet to be loaded
    if (typeof L === 'undefined') {
        console.error('Leaflet JS not loaded');
        return;
    }

    // Initialize map for region {{ $id }}
    const mapId = 'map-region-{{ $id }}';
    const mapElement = document.getElementById(mapId);
    
    if (!mapElement) {
        console.error('Map container not found:', mapId);
        return;
    }

    // Create map instance
    const map = L.map(mapId).setView({{ json_encode($mapCenter) }}, {{ $mapZoom }});
    
    // Add Esri World Imagery tile layer (Satellite view)
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri',
        maxZoom: 19
    }).addTo(map);
    
    // Define irrigation areas with different status
    const areas = [
        {
            name: 'Area A - Tersiram',
            coords: [
                [{{ $mapCenter[0] + 0.002 }}, {{ $mapCenter[1] - 0.002 }}],
                [{{ $mapCenter[0] + 0.002 }}, {{ $mapCenter[1] + 0.001 }}],
                [{{ $mapCenter[0] + 0.004 }}, {{ $mapCenter[1] + 0.001 }}],
                [{{ $mapCenter[0] + 0.004 }}, {{ $mapCenter[1] - 0.002 }}]
            ],
            color: '#22c55e',
            status: 'Tersiram',
            size: '18 × 19'
        },
        {
            name: 'Area B - 3 Hari',
            coords: [
                [{{ $mapCenter[0] - 0.001 }}, {{ $mapCenter[1] - 0.003 }}],
                [{{ $mapCenter[0] - 0.001 }}, {{ $mapCenter[1] - 0.001 }}],
                [{{ $mapCenter[0] + 0.001 }}, {{ $mapCenter[1] - 0.001 }}],
                [{{ $mapCenter[0] + 0.001 }}, {{ $mapCenter[1] - 0.003 }}]
            ],
            color: '#eab308',
            status: 'Tidak tersiram selama 3 hari',
            size: '15 × 12'
        },
        {
            name: 'Area C - >3 Hari',
            coords: [
                [{{ $mapCenter[0] - 0.003 }}, {{ $mapCenter[1] + 0.001 }}],
                [{{ $mapCenter[0] - 0.003 }}, {{ $mapCenter[1] + 0.003 }}],
                [{{ $mapCenter[0] - 0.001 }}, {{ $mapCenter[1] + 0.003 }}],
                [{{ $mapCenter[0] - 0.001 }}, {{ $mapCenter[1] + 0.001 }}]
            ],
            color: '#f97316',
            status: 'Tidak tersiram > 3 hari',
            size: '12 × 15'
        }
    ];
    
    // Add polygons to map
    areas.forEach(area => {
        const polygon = L.polygon(area.coords, {
            color: area.color,
            fillColor: area.color,
            fillOpacity: 0.6,
            weight: 2
        }).addTo(map);
        
        // Add popup with info
        polygon.bindPopup(`
            <div style="font-family: system-ui, -apple-system, sans-serif;">
                <strong style="font-size: 14px; color: #4F4F4F;">${area.name}</strong><br>
                <span style="font-size: 12px; color: #808080;">Status: ${area.status}</span><br>
                <span style="font-size: 12px; color: #808080;">Ukuran: ${area.size}</span>
            </div>
        `);
    });
});
</script>
@endpush
