@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h6 class=" font-bold text-[#4F4F4F]">Beranda</h6>
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
                    class="btn-3d-green px-6 py-2.5 flex items-center gap-2">
                    Nyalakan Semua Pompa
                </button>

                <button
                    class="btn-3d-red px-6 py-2.5 flex items-center gap-2">
                    Matikan Semua Pompa
                </button>
            </div>
        </div>

        <div class="flex justify-end mb-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <span class="text-sm text-gray-700">Irigasi Otomatis Semua Lahan</span>
                <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                    x-data="{ on: false }" @click="on = !on" :class="on ? 'bg-primary' : 'bg-gray-300'">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                        :class="on ? 'translate-x-6' : 'translate-x-1'"></span>
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-iot-sensor-card id="1" name="IoT 1" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="47,38%"
                flowRate="33 L / Menit" volume="78 Liter" pumpStatus="Aktif" lastUpdate="5 menit yang lalu"
                :isPumpOn="true" :isAutoIrrigation="false" />

            <x-iot-sensor-card id="2" name="IoT 2" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="47,38%"
                flowRate="33 L / Menit" volume="78 Liter" pumpStatus="Mati" lastUpdate="5 menit yang lalu" :isPumpOn="false"
                :isAutoIrrigation="false" />

            <x-iot-sensor-card id="3" name="IoT 3" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="47,38%"
                flowRate="33 L / Menit" volume="78 Liter" pumpStatus="Aktif" lastUpdate="5 menit yang lalu"
                :isPumpOn="true" :isAutoIrrigation="false" />

            <x-iot-sensor-card id="4" name="IoT 4" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="45,20%"
                flowRate="31 L / Menit" volume="72 Liter" pumpStatus="Aktif" lastUpdate="3 menit yang lalu"
                :isPumpOn="true" :isAutoIrrigation="true" />

            <x-iot-sensor-card id="5" name="IoT 5" location="Dawuhan, Kab. Banyumas" sensorStatus="Terhubung" humidity="50,15%"
                flowRate="35 L / Menit" volume="80 Liter" pumpStatus="Mati" lastUpdate="2 menit yang lalu" :isPumpOn="false"
                :isAutoIrrigation="false" />
        </div>
    </div>
@endsection
