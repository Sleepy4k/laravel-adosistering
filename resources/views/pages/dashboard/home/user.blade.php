@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    @role(config('rbac.role.default'))
        {{-- Store Firebase module path from Vite --}}
        <script>
            window.__FIREBASE_MODULE_PATH__ = "{{ Vite::asset('resources/js/firebase.js') }}";
        </script>

        {{-- iotDashboard() is now loaded via resources/js/components/iot-dashboard.js (through app.js) --}}

        <div class="max-w-7xl mx-auto" x-data="iotDashboard({{ Js::from($firebase ?? []) }})">
            <!-- Connection Status -->
            <div x-show="!isConnected && !isLoading" class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>Menghubungkan ke Firebase...</span>
                </div>
            </div>

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
                    tanah dapat diatur pada menu <a href="{{ route('user.pengaturan') }}" class="underline text-primary-color font-medium">pengaturan</a>. Ketika irigasi otomatis
                    diaktifkan, maka irigasi manual akan nonaktif.
                </p>
            </div>

            {{-- IoT Irrigation Map Section --}}
            <div class="bg-white p-5 rounded-2xl border border-[#C2C2C2] overflow-hidden my-6">
                <h2 class="text-xl font-semibold text-[#4F4F4F] mb-4">Peta Area Irigasi IoT</h2>
                <x-user.iot-irrigation-map />
            </div>

            <!-- Dynamic Block Cards from Firebase -->
            <div id="dynamic-blocks-container">
                {{-- Loading State --}}
                <div x-show="isLoading" class="bg-white rounded-2xl border border-[#C2C2C2] p-8 mb-6">
                    <div class="flex items-center justify-center">
                        <svg class="animate-spin h-8 w-8 text-green-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600">Memuat data dari Firebase...</span>
                    </div>
                </div>

                {{-- Empty State --}}
                <template x-if="!isLoading && firebaseBlocks.length === 0">
                    <div class="bg-gray-50 border border-gray-200 text-gray-600 px-6 py-8 rounded-2xl mb-6 text-center">
                        <p class="text-lg font-medium">Tidak ada data blok tersedia</p>
                        <p class="text-sm mt-1">Data akan muncul ketika tersedia di Firebase</p>
                    </div>
            </template>

            {{-- Dynamic Blocks from Firebase — using Alpine-aware component --}}
            <template x-for="(block, blockIdx) in firebaseBlocks" :key="block.name">
                @include('components.user.block-accordion')
            </template>
        </div>        </div>
    @else
        {{-- superadminDashboard() is now loaded via resources/js/components/superadmin-dashboard.js (through app.js) --}}

        <div class="max-w-7xl mx-auto" x-data="superadminDashboard()">
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
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                    <a href="{{ route('home') }}"
                        class="text-base hover:text-primary-color transition-colors">Beranda</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-base text-gray-400">List</span>
                </nav>
            </div>

            <!-- Filters Section -->
            <x-admin.iot-filter role="superadmin" />

            <!-- IoT Sensor List -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="iot-sensor-list">
                @php
                    $iotSensors = [
                        [
                            'id' => '1',
                            'userName' => 'Budi Santoso',
                            'location' => 'Dawuhan, Kab. Banyumas',
                            'sensorStatus' => 'Terhubung',
                            'humidity' => '47,38%',
                            'flowRate' => '33 L / Menit',
                            'volume' => '78 Liter',
                            'pumpStatus' => 'Aktif',
                            'lastUpdate' => '5 menit yang lalu',
                            'isPumpOn' => true,
                            'profileImage' => null,
                        ],
                        [
                            'id' => '2',
                            'userName' => 'Siti Nurhaliza',
                            'location' => 'Dawuhan, Kab. Banyumas',
                            'sensorStatus' => 'Terhubung',
                            'humidity' => '52,15%',
                            'flowRate' => '28 L / Menit',
                            'volume' => '65 Liter',
                            'pumpStatus' => 'Mati',
                            'lastUpdate' => '3 menit yang lalu',
                            'isPumpOn' => false,
                            'profileImage' => asset('assets/images/users/user2.jpg'),
                        ],
                        [
                            'id' => '3',
                            'userName' => 'Ahmad Wijaya',
                            'location' => 'Kawista, Kab. Banyumas',
                            'sensorStatus' => 'Terputus',
                            'humidity' => '0%',
                            'flowRate' => '0 L / Menit',
                            'volume' => '0 Liter',
                            'pumpStatus' => 'Mati',
                            'lastUpdate' => '1 jam yang lalu',
                            'isPumpOn' => false,
                            'profileImage' => null,
                        ],
                        [
                            'id' => '4',
                            'userName' => 'Dewi Lestari',
                            'location' => 'Selatan, Kab. Banyumas',
                            'sensorStatus' => 'Terhubung',
                            'humidity' => '45,20%',
                            'flowRate' => '31 L / Menit',
                            'volume' => '72 Liter',
                            'pumpStatus' => 'Aktif',
                            'lastUpdate' => '2 menit yang lalu',
                            'isPumpOn' => true,
                            'profileImage' => null,
                        ],
                        [
                            'id' => '5',
                            'userName' => 'Eko Prasetyo',
                            'location' => 'Utara, Kab. Banyumas',
                            'sensorStatus' => 'Terhubung',
                            'humidity' => '50,15%',
                            'flowRate' => '35 L / Menit',
                            'volume' => '80 Liter',
                            'pumpStatus' => 'Aktif',
                            'lastUpdate' => '1 menit yang lalu',
                            'isPumpOn' => true,
                            'profileImage' => null,
                        ],
                        [
                            'id' => '6',
                            'userName' => 'Maya Sari',
                            'location' => 'Barat, Kab. Banyumas',
                            'sensorStatus' => 'Terhubung',
                            'humidity' => '38,95%',
                            'flowRate' => '25 L / Menit',
                            'volume' => '55 Liter',
                            'pumpStatus' => 'Mati',
                            'lastUpdate' => '8 menit yang lalu',
                            'isPumpOn' => false,
                            'profileImage' => null,
                        ],
                    ];
                @endphp

                @foreach ($iotSensors as $sensor)
                    <x-admin.iot-sensor-card :id="$sensor['id']" :userName="$sensor['userName']" :location="$sensor['location']" :sensorStatus="$sensor['sensorStatus']"
                        :humidity="$sensor['humidity']" :flowRate="$sensor['flowRate']" :volume="$sensor['volume']" :pumpStatus="$sensor['pumpStatus']" :lastUpdate="$sensor['lastUpdate']"
                        :isPumpOn="$sensor['isPumpOn']" :profileImage="$sensor['profileImage']" />
                @endforeach
            </div>

            <!-- Empty State (when no data found) -->
            <div class="text-center py-12 hidden" id="empty-state">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data ditemukan</h3>
                <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
            </div>
        </div>
    @endrole
@endsection

