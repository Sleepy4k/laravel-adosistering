@extends('layouts.admin')

@section('title', 'Beranda Admin')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="adminDashboard()">
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
                <a href="{{ route('admin.dashboard') }}" class="text-base hover:text-primary-color transition-colors">Beranda</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-base text-gray-400">List</span>
            </nav>
        </div>

        <!-- Filters Section -->
        <x-admin.iot-filter />

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
                <x-admin.iot-sensor-card :id="$sensor['id']" :userName="$sensor['userName']" :location="$sensor['location']"
                    :sensorStatus="$sensor['sensorStatus']" :humidity="$sensor['humidity']" :flowRate="$sensor['flowRate']" :volume="$sensor['volume']" :pumpStatus="$sensor['pumpStatus']"
                    :lastUpdate="$sensor['lastUpdate']" :isPumpOn="$sensor['isPumpOn']" :profileImage="$sensor['profileImage']" />
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

    <!-- Alpine.js Component -->
    <script>
        function adminDashboard() {
            return {
                searchTerm: '',
                selectedSensorStatus: '',
                selectedPumpStatus: '',

                filterCards() {
                    const cards = document.querySelectorAll('.iot-card');
                    let visibleCount = 0;

                    cards.forEach(card => {
                        const sensorStatus = card.dataset.sensorStatus;
                        const pumpStatus = card.dataset.pumpStatus;
                        const userName = card.dataset.userName?.toLowerCase() || '';
                        const iotName = card.dataset.iotName?.toLowerCase() || '';

                        // Search filter
                        const matchesSearch = this.searchTerm === '' ||
                            userName.includes(this.searchTerm.toLowerCase()) ||
                            iotName.includes(this.searchTerm.toLowerCase());

                        // Sensor status filter
                        const matchesSensorStatus = this.selectedSensorStatus === '' ||
                            sensorStatus === this.selectedSensorStatus;

                        // Pump status filter
                        const matchesPumpStatus = this.selectedPumpStatus === '' ||
                            pumpStatus === this.selectedPumpStatus;

                        // Show/hide card
                        if (matchesSearch && matchesSensorStatus && matchesPumpStatus) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Show/hide empty state
                    const emptyState = document.getElementById('empty-state');
                    if (visibleCount === 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                    }
                },

                init() {
                    // Watch for changes and filter
                    this.$watch('searchTerm', () => this.filterCards());
                    this.$watch('selectedSensorStatus', () => this.filterCards());
                    this.$watch('selectedPumpStatus', () => this.filterCards());
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
