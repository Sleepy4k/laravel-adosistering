@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')

    <script>
        window.iotDashboard = function(firebaseConfig) {
            return {
                isConnected: false,
                isLoading: true,
                firebaseBlocks: [],
                loadingStates: {},
                retryCount: 0,
                maxRetries: 10,
                
                init() {
                    // Initialize IoT Dashboard
                    this.waitForFirebase(firebaseConfig);
                },

                waitForFirebase(config) {
                    if (!window.FirebaseIoT) {
                        if (this.retryCount < this.maxRetries) {
                            this.retryCount++;
                            setTimeout(() => this.waitForFirebase(config), 300);
                        } else {
                            console.error('❌ FirebaseIoT library tidak tersedia');
                            this.isLoading = false;
                        }
                        return;
                    }

                    if (config && config.apiKey) {
                        const db = window.FirebaseIoT.initialize(config);
                        if (db) {
                            this.isConnected = true;
                            this.listenToBlocks();
                        } else {
                            console.error('❌ Firebase gagal diinisialisasi');
                            this.isLoading = false;
                        }
                    } else {
                        console.error('❌ Firebase config tidak ditemukan');
                        this.isLoading = false;
                    }
                },

                getLoadingKey(blockName, sprayerName) {
                    return blockName + '/' + sprayerName;
                },

                isSprayerLoading(blockName, sprayerName) {
                    return this.loadingStates[this.getLoadingKey(blockName, sprayerName)] || false;
                },

                setSprayerLoading(blockName, sprayerName, loading) {
                    const key = this.getLoadingKey(blockName, sprayerName);
                    this.loadingStates[key] = loading;
                    // Force Alpine reactivity
                    this.loadingStates = { ...this.loadingStates };
                },

                listenToBlocks() {
                    window.FirebaseIoT.listenToMAOS((result) => {
                        this.isLoading = false;
                        
                        if (result.blocks && result.blocks.length > 0) {
                            result.blocks.forEach(block => {
                                const existing = this.firebaseBlocks.find(b => b.name === block.name);
                                block.isOpen = existing ? existing.isOpen : true;
                            });
                            
                            this.firebaseBlocks = result.blocks;
                            // Data updated silently, no console log
                        }
                    });
                },

                async toggleRelay(blockName, sprayerName, turnOn) {
                    // Check if already loading
                    if (this.isSprayerLoading(blockName, sprayerName)) {
                        return;
                    }

                    this.setSprayerLoading(blockName, sprayerName, true);

                    try {
                        const success = await window.FirebaseIoT.controlRelay(blockName, sprayerName, turnOn);
                        if (!success) {
                            alert('Gagal mengubah status pompa');
                        }
                    } catch (error) {
                        console.error('Error toggle relay:', error);
                        alert('Error: ' + error.message);
                    } finally {
                        // Small delay to let Firebase update first
                        setTimeout(() => {
                            this.setSprayerLoading(blockName, sprayerName, false);
                        }, 500);
                    }
                },

                async turnOnAllSprayers(blockName) {
                    const block = this.firebaseBlocks.find(b => b.name === blockName);
                    if (!block) return;
                    
                    for (const sprayer of block.sprayers) {
                        await this.toggleRelay(blockName, sprayer.name, true);
                    }
                },

                async turnOffAllSprayers(blockName) {
                    const block = this.firebaseBlocks.find(b => b.name === blockName);
                    if (!block) return;
                    
                    for (const sprayer of block.sprayers) {
                        await this.toggleRelay(blockName, sprayer.name, false);
                    }
                },

                formatTimestamp(timestamp) {
                    if (!timestamp || timestamp === 0) {
                        return new Date().toLocaleString('id-ID');
                    }
                    return new Date(timestamp * 1000).toLocaleString('id-ID');
                },

                formatRelativeTime(minutesAgo) {
                    // Firebase timestamp sudah dalam format "berapa menit yang lalu"
                    // Contoh: 5 = 5 menit yang lalu, 120 = 120 menit yang lalu
                    
                    if (!minutesAgo || minutesAgo === 0) {
                        return 'Baru saja';
                    }
                    
                    // Konversi ke number jika string
                    const minutes = Number(minutesAgo);
                    
                    // Kurang dari 1 menit
                    if (minutes < 1) {
                        return 'Baru saja';
                    }
                    // 1-59 menit
                    else if (minutes < 60) {
                        return `${minutes} menit yang lalu`;
                    }
                    // 1-23 jam (60-1439 menit)
                    else if (minutes < 1440) {
                        const hours = Math.floor(minutes / 60);
                        return `${hours} jam yang lalu`;
                    }
                    // 1-29 hari (1440-43199 menit)
                    else if (minutes < 43200) {
                        const days = Math.floor(minutes / 1440);
                        return `${days} hari yang lalu`;
                    }
                    // 30 hari ke atas
                    else {
                        const months = Math.floor(minutes / 43200);
                        if (months === 1) {
                            return '1 bulan yang lalu';
                        }
                        return `${months} bulan yang lalu`;
                    }
                }
            }
        }
    </script>

    @role(config('rbac.role.default'))
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
                    tanah dapat diatur pada menu <a href="#" class="underline">pengaturan</a>. Ketika irigasi otomatis
                    diaktifkan, maka irigasi manual akan
                    nonaktif.
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

                {{-- Dynamic Blocks from Firebase --}}
                <template x-for="(block, blockIdx) in firebaseBlocks" :key="block.name">
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

                        {{-- Sprayers List --}}
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
                                    <div class="bg-blue rounded-xl border border-gray-200 p-5">
                                        {{-- Sprayer Header --}}
                                        <h3 class="text-lg font-bold text-[#4F8936] mb-2" x-text="sprayer.name"></h3>
                                        
                                        <p class="text-xs text-[#4F4F4F] mb-4">Desa Mernek, Kecamatan Maos, Kabupaten Cilacap, Jawa Tengah</p>

                                        {{-- Sensor Status --}}
                                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-[#C2C2C2]">
                                            <span class="text-sm font-medium text-[#4F4F4F]">Sensor Data</span>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full"
                                                  :class="sprayer.sensorConnected !== false ? 'bg-[#D4F4DD] text-[#186D3C]' : 'bg-[#FDF1B9] text-[#947E11]'"
                                                  x-text="sprayer.sensorConnected !== false ? 'Terhubung' : 'Gangguan Sensor'"></span>
                                        </div>

                                        {{-- Error Message (if sensor has issue) --}}
                                        <div x-show="sprayer.sensorConnected === false" class="mb-4">
                                            <p class="text-xs text-[#C71A34] italic">*Sensor Pompa mengalami masalah</p>
                                        </div>

                                        {{-- Sensor Readings --}}
                                        <div class="space-y-3 mb-4 pt-1">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-[#4F4F4F]">Kelembaban Tanah</span>
                                                <span class="text-sm font-semibold text-[#186D3C]" x-text="sprayer.moisture + '%'"></span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-[#4F4F4F]">Debit Air Rata-Rata</span>
                                                <span class="text-sm font-semibold text-[#186D3C]" x-text="sprayer.flowRate + ' L / Menit'"></span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-[#4F4F4F]">Total Volume Air</span>
                                                <span class="text-sm font-semibold text-[#186D3C]" x-text="sprayer.totalVolume + ' Liter'"></span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-[#4F4F4F]">Pompa</span>
                                                <span class="px-3 py-1 text-xs font-medium rounded-full"
                                                      :class="sprayer.relay === 'ON' ? 'bg-[#D4F4DD] text-[#186D3C]' : 'bg-[#ECECEC] text-[#4F4F4F]'"
                                                      x-text="sprayer.relay === 'ON' ? 'Aktif' : 'Mati'"></span>
                                            </div>
                                        </div>

                                        {{-- Last Update (Float Right) --}}
                                        <div class="mb-4 text-right">
                                            <p class="text-xs text-[#4F4F4F] italic" x-text="'Terakhir update ' + formatRelativeTime(sprayer.timestamp)"></p>
                                        </div>

                                        {{-- Controls - Toggle and Checkbox in one row --}}
                                        <div class="flex items-center justify-between pt-4 border-t border-[#C2C2C2]">
                                            {{-- Toggle ON/OFF (Left Side) --}}
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-medium text-[#4F4F4F]" x-text="sprayer.relay === 'ON' ? 'ON' : 'OFF'"></span>
                                                <button type="button" 
                                                        @click="toggleRelay(block.name, sprayer.name, sprayer.relay !== 'ON')"
                                                        :disabled="isSprayerLoading(block.name, sprayer.name)"
                                                        class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors"
                                                        :class="[
                                                            sprayer.relay === 'ON' ? 'bg-[#67B744]' : 'bg-gray-300',
                                                            isSprayerLoading(block.name, sprayer.name) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                                        ]">
                                                    <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform shadow-md"
                                                          :class="sprayer.relay === 'ON' ? 'translate-x-6' : 'translate-x-1'"></span>
                                                </button>
                                            </div>
                                            
                                            {{-- Irigasi Otomatis Checkbox (Right Side) --}}
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-medium text-[#4F4F4F]">Irigasi Otomatis</span>
                                                <input type="checkbox" class="checkbox-green" :checked="sprayer.autoIrrigation || false">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    @else
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

        <!-- Alpine.js Component -->
        <script>
            function superadminDashboard() {
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
    @endrole
@endsection
