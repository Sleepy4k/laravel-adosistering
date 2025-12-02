@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="iotDashboard()">
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Beranda</h1>
                <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <p class="text-sm text-gray-600 italic">
                Irigasi Otomatis berarti menyalakan pompa berdasarkan kelembaban tanah secara otomatis. Batas kelembaban
                tanah dapat diatur pada menu <a href="#" class="underline">pengaturan</a>. Ketika irigasi otomatis diaktifkan, maka irigasi manual akan
                nonaktif.
            </p>
        </div>

        <div class="flex items-center justify-end gap-3 mb-6">
            <div class="flex items-center gap-3">
                <button
                    @click="turnOnAllPumps()"
                    class="btn-3d-green px-6 py-2.5 flex items-center gap-2">
                    Nyalakan Semua Pompa
                </button>

                <button
                    @click="turnOffAllPumps()"
                    class="btn-3d-red px-6 py-2.5 flex items-center gap-2">
                    Matikan Semua Pompa
                </button>
            </div>
        </div>

        <div class="flex justify-end mb-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <span class="text-sm text-gray-700">Irigasi Otomatis Semua Lahan</span>
                <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                    @click="toggleAllAutoIrrigation()" 
                    :class="allAutoIrrigation ? 'bg-primary' : 'bg-gray-300'">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                        :class="allAutoIrrigation ? 'translate-x-6' : 'translate-x-1'"></span>
                </button>
            </label>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Kelembaban Tanah (Dynamic Badge) -->
            <div class="bg-white rounded-2xl border border-[#C2C2C2] p-6" 
                 x-data="{ humidity: 47.38 }"
                 x-init="$watch('humidity', value => console.log('Humidity:', value))">
                <p class="font-bold text-[#4F4F4F] mb-2">Kelembaban Tanah</p>
                <div class="flex items-end justify-between">
                    <p class="text-[28px] font-bold text-[#4F4F4F]" x-text="humidity.toFixed(2) + '%'"></p>
                    <!-- Badge Dinamis: Basah jika >= 60%, Kering jika < 60% -->
                    <span x-show="humidity >= 60" 
                          class="px-3 py-1 text-xs font-medium rounded-full bg-[#E6F4FF] text-[#0066CC]">
                        Basah
                    </span>
                    <span x-show="humidity < 60" 
                          class="px-3 py-1 text-xs font-medium rounded-full bg-[#FDF1B9] text-[#947E11]">
                        Kering
                    </span>
                </div>
            </div>

            <!-- Card 2: Debit Rata-Rata -->
            <div class="bg-white rounded-2xl border border-[#C2C2C2] p-6">
                <p class="font-bold text-[#4F4F4F] mb-2">Debit Rata-Rata</p>
                <p class="text-[28px] font-bold text-[#4F4F4F]">38,57 Liter/Menit</p>
            </div>

            <!-- Card 3: Total Volume Air -->
            <div class="bg-white rounded-2xl border border-[#C2C2C2] p-6">
                <p class="font-bold text-[#4F4F4F] mb-2">Total Volume Air</p>
                <p class="text-[28px] font-bold text-[#4F4F4F]">78 Liter</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <x-user.iot-sensor-card id="1" name="Blok A" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="47,38%"
                flowRate="33 L / Menit" volume="78 Liter" pumpStatus="Aktif" lastUpdate="5 menit yang lalu"
                :isPumpOn="true" :isAutoIrrigation="false" />

            <x-user.iot-sensor-card id="2" name="Blok B" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="47,38%"
                flowRate="33 L / Menit" volume="78 Liter" pumpStatus="Mati" lastUpdate="5 menit yang lalu" :isPumpOn="false"
                :isAutoIrrigation="false" />

            <x-user.iot-sensor-card id="3" name="Region C" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="47,38%"
                flowRate="33 L / Menit" volume="78 Liter" pumpStatus="Aktif" lastUpdate="5 menit yang lalu"
                :isPumpOn="true" :isAutoIrrigation="false" />
        </div>

        {{-- Map Section --}}
        <div class="bg-white p-5 rounded-2xl border border-[#C2C2C2] overflow-hidden">
            <div class="relative">
                <div id="map" class="w-full h-[400px] rounded-2xl"></div>
            </div>
        </div>

        {{-- Map Legend --}}
        <div class="mt-6 bg-white rounded-2xl border border-[#C2C2C2] p-6">
            <h3 class="text-lg font-semibold text-[#4F4F4F] mb-4">Keterangan Status Area</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Green - Safe --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-[#22c55e] border-2 border-white shadow-md"></div>
                    <div>
                        <p class="text-sm font-semibold text-[#4F4F4F]">Tersiram</p>
                        <p class="text-xs text-gray-500">Status: Aman</p>
                    </div>
                </div>

                {{-- Yellow - Warning --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-[#eab308] border-2 border-white shadow-md"></div>
                    <div>
                        <p class="text-sm font-semibold text-[#4F4F4F]">3 Hari</p>
                        <p class="text-xs text-gray-500">Tidak tersiram 3 hari</p>
                    </div>
                </div>

                {{-- Orange - Danger --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-[#f97316] border-2 border-white shadow-md"></div>
                    <div>
                        <p class="text-sm font-semibold text-[#4F4F4F]">&gt;3 Hari</p>
                        <p class="text-xs text-gray-500">Perlu penyiraman</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaflet CSS --}}
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .leaflet-container {
            border-radius: 1rem;
        }
    </style>
    @endpush

    {{-- Leaflet JS & Map Initialization --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for Leaflet to be loaded
            if (typeof L === 'undefined') {
                console.error('Leaflet JS not loaded');
                return;
            }

            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.error('Map container not found');
                return;
            }

            // Map center for Dawuhan, Banyumas area
            const mapCenter = [-7.5595, 109.0134];
            const mapZoom = 15;

            // Create map instance
            const map = L.map('map').setView(mapCenter, mapZoom);
            
            // Add Esri World Imagery tile layer (Satellite view)
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri',
                maxZoom: 19
            }).addTo(map);
            
            // Define irrigation areas with different status
            const areas = [
                {
                    name: 'Blok A - Tersiram',
                    coords: [
                        [mapCenter[0] + 0.002, mapCenter[1] - 0.002],
                        [mapCenter[0] + 0.002, mapCenter[1] + 0.001],
                        [mapCenter[0] + 0.004, mapCenter[1] + 0.001],
                        [mapCenter[0] + 0.004, mapCenter[1] - 0.002]
                    ],
                    color: '#22c55e',
                    status: 'Tersiram',
                    size: '18 × 19'
                },
                {
                    name: 'Blok B - 3 Hari',
                    coords: [
                        [mapCenter[0] - 0.001, mapCenter[1] - 0.003],
                        [mapCenter[0] - 0.001, mapCenter[1] - 0.001],
                        [mapCenter[0] + 0.001, mapCenter[1] - 0.001],
                        [mapCenter[0] + 0.001, mapCenter[1] - 0.003]
                    ],
                    color: '#eab308',
                    status: 'Tidak tersiram selama 3 hari',
                    size: '15 × 12'
                },
                {
                    name: 'Region C - >3 Hari',
                    coords: [
                        [mapCenter[0] - 0.003, mapCenter[1] + 0.001],
                        [mapCenter[0] - 0.003, mapCenter[1] + 0.003],
                        [mapCenter[0] - 0.001, mapCenter[1] + 0.003],
                        [mapCenter[0] - 0.001, mapCenter[1] + 0.001]
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

    <script>
        function iotDashboard() {
            return {
                allAutoIrrigation: false,

                init() {
                    // Listen untuk event dari card ketika checkbox di-uncheck
                    window.addEventListener('irrigation-single-changed', (e) => {
                        if (!e.detail.checked) {
                            // Jika ada yang uncheck, matikan switch "Semua Lahan"
                            this.allAutoIrrigation = false;
                        }
                    });
                },

                // Nyalakan semua pompa
                turnOnAllPumps() {
                    // Dispatch custom event untuk semua card
                    window.dispatchEvent(new CustomEvent('pump-all', { detail: { status: true } }));
                },

                // Matikan semua pompa
                turnOffAllPumps() {
                    // Dispatch custom event untuk semua card
                    window.dispatchEvent(new CustomEvent('pump-all', { detail: { status: false } }));
                },

                // Toggle irigasi otomatis semua lahan
                toggleAllAutoIrrigation() {
                    this.allAutoIrrigation = !this.allAutoIrrigation;
                    // Dispatch custom event untuk semua checkbox irigasi
                    window.dispatchEvent(new CustomEvent('irrigation-all', { detail: { checked: this.allAutoIrrigation } }));
                }
            }
        }
    </script>
@endsection
