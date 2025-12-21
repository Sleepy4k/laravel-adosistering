@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="iotDashboard()">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Beranda</h1>
                <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
            </div>
        </div>

        <!-- Info Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-4 mb-6">
            <p class="text-sm text-gray-600 italic">
                Irigasi Otomatis berarti menyalakan pompa berdasarkan kelembaban tanah secara otomatis. Batas kelembaban
                tanah dapat diatur pada menu <a href="#" class="underline">pengaturan</a>. Ketika irigasi otomatis diaktifkan, maka irigasi manual akan
                nonaktif.
            </p>
        </div>

        <!-- Block Cards with Accordion -->
        <x-user.block-accordion-card 
            blockId="A"
            blockName="Blok A"
            avgHumidity="47,38%"
            avgFlowRate="38,57 Liter / Menit"
            totalVolume="78 Liter"
            :sprayers="[
                [
                    'id' => '1',
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
                ],
                [
                    'id' => '2',
                    'name' => 'Sprayer 2',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Gangguan Sensor',
                    'humidity' => '47,38%',
                    'flowRate' => '33 L / Menit',
                    'volume' => '78 Liter',
                    'pumpStatus' => 'Mati',
                    'lastUpdate' => '5 menit yang lalu',
                    'isPumpOn' => false,
                    'isAutoIrrigation' => false
                ]
            ]"
        />

        <x-user.block-accordion-card 
            blockId="B"
            blockName="Blok B"
            avgHumidity="52,15%"
            avgFlowRate="35,20 Liter / Menit"
            totalVolume="65 Liter"
            :sprayers="[
                [
                    'id' => '3',
                    'name' => 'Sprayer 1',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Terhubung',
                    'humidity' => '52,15%',
                    'flowRate' => '35 L / Menit',
                    'volume' => '65 Liter',
                    'pumpStatus' => 'Mati',
                    'lastUpdate' => '3 menit yang lalu',
                    'isPumpOn' => false,
                    'isAutoIrrigation' => false
                ],
                [
                    'id' => '4',
                    'name' => 'Sprayer 2',
                    'location' => 'Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah',
                    'sensorStatus' => 'Terhubung',
                    'humidity' => '52,15%',
                    'flowRate' => '35 L / Menit',
                    'volume' => '65 Liter',
                    'pumpStatus' => 'Mati',
                    'lastUpdate' => '3 menit yang lalu',
                    'isPumpOn' => false,
                    'isAutoIrrigation' => false
                ]
            ]"
        />


        {{-- IoT Irrigation Map Section --}}
        <div class="bg-white p-5 rounded-2xl border border-[#C2C2C2] overflow-hidden mt-6">
            <h2 class="text-xl font-semibold text-[#4F4F4F] mb-4">Peta Area Irigasi IoT</h2>
            <x-user.iot-irrigation-map-editable />
        </div>
        {{-- Map Legend --}}
        <div class="mt-6 bg-white rounded-2xl border border-[#C2C2C2] p-6">
            <h3 class="text-lg font-semibold text-[#4F4F4F] mb-4">Keterangan Perangkat</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Sprayer --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#22c55e] border-2 border-white shadow-md flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 32 32" fill="white">
                            <circle cx="16" cy="16" r="3" fill="white"/>
                            <line x1="16" y1="8" x2="16" y2="12" stroke="white" stroke-width="2"/>
                            <line x1="16" y1="20" x2="16" y2="24" stroke="white" stroke-width="2"/>
                            <line x1="8" y1="16" x2="12" y2="16" stroke="white" stroke-width="2"/>
                            <line x1="20" y1="16" x2="24" y2="16" stroke="white" stroke-width="2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#4F4F4F]">Sprayer</p>
                        <p class="text-xs text-gray-500">Alat penyiram</p>
                    </div>
                </div>

                {{-- Sensor --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-[#ef4444] border-2 border-white shadow-md flex items-center justify-center">
                        <span class="text-white font-bold text-xs">S</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#4F4F4F]">Sensor</p>
                        <p class="text-xs text-gray-500">Sensor kelembaban</p>
                    </div>
                </div>

                {{-- Tank --}}
                <div class="flex items-center gap-3">
                    <div class="w-12 h-8 rounded bg-[#ef4444] border-2 border-white shadow-md flex items-center justify-center">
                        <span class="text-white font-bold text-xs">TN</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#4F4F4F]">Tangki</p>
                        <p class="text-xs text-gray-500">Tangki air</p>
                    </div>
                </div>

                {{-- Area Pin --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <svg width="24" height="32" viewBox="0 0 24 32" fill="none">
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 9 12 20 12 20s12-11 12-20c0-6.627-5.373-12-12-12z" fill="#FFD700"/>
                            <circle cx="12" cy="12" r="4" fill="white"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#4F4F4F]">Label Area</p>
                        <p class="text-xs text-gray-500">Penanda blok</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function iotDashboard() {
            return {
                init() {
                    console.log('IoT Dashboard initialized');
                }
            }
        }
    </script>
@endsection
