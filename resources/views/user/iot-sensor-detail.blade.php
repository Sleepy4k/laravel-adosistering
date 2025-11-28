@extends('layouts.user')

@section('title', 'Detail IoT Sensor - ' . $sensor['name'])

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('user.dashboard') }}"
                class="inline-flex items-center gap-2 text-[#4F4F4F] hover:text-primary-color transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="font-medium">Kembali ke Dashboard</span>
            </a>
        </div>

        {{-- Page Title --}}
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 sm:py-6 px-4 sm:px-6 mb-6">
            <p class="text-2xl font-bold text-[#4F4F4F]">Detail IoT Sensor - {{ $sensor['name'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Monitoring dan kontrol sistem irigasi otomatis</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Kelembaban Tanah (Dynamic Badge) -->
            <div class="bg-white rounded-2xl border border-[#C2C2C2] p-6" x-data="{ humidity: 47.38 }" x-init="$watch('humidity', value => console.log('Humidity:', value))">
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

        {{-- IoT Sensor Detail Card with Map --}}
        <x-iot-sensor-card-detail :id="$sensor['id']" :name="$sensor['name']" :location="$sensor['location']" :sensorStatus="$sensor['sensorStatus']" :humidity="$sensor['humidity']"
            :flowRate="$sensor['flowRate']" :volume="$sensor['volume']" :pumpStatus="$sensor['pumpStatus']" :lastUpdate="$sensor['lastUpdate']" :isPumpOn="$sensor['isPumpOn']" :isAutoIrrigation="$sensor['isAutoIrrigation']"
            :mapCenter="$sensor['mapCenter']" :mapZoom="$sensor['mapZoom']" />

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
@endsection
