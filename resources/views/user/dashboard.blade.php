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
                tanah dapat diatur pada menu <a href="#" class="underline">pengaturan</a>. Ketika irigasi otomatis
                diaktifkan, maka irigasi manual akan
                nonaktif.
            </p>
        </div>

        {{-- IoT Irrigation Map Section --}}
        <div class="bg-white p-5 rounded-2xl border border-[#C2C2C2] overflow-hidden my-6">
            <h2 class="text-xl font-semibold text-[#4F4F4F] mb-4">Peta Area Irigasi IoT</h2>
            <x-user.iot-irrigation-map-editable />
        </div>

        <!-- Block Cards with Accordion -->
        <x-user.block-accordion-card blockId="A" blockName="Blok A" avgHumidity="47,38%"
            avgFlowRate="38,57 Liter / Menit" totalVolume="78 Liter" :sprayers="[
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
                    'isAutoIrrigation' => false,
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
                    'isAutoIrrigation' => false,
                ],
            ]" />

        <x-user.block-accordion-card blockId="B" blockName="Blok B" avgHumidity="52,15%"
            avgFlowRate="35,20 Liter / Menit" totalVolume="65 Liter" :sprayers="[
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
                    'isAutoIrrigation' => false,
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
                    'isAutoIrrigation' => false,
                ],
            ]" />

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
