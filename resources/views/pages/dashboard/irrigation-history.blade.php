@extends('layouts.dashboard')

@section('title', 'Riwayat Irigasi')

@section('content')
    <div class="w-full max-w-7xl mx-auto py-6" x-data="irrigationHistory()" x-init="init()">
        <!-- Header -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Riwayat Irigasi</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" loading="lazy" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        @can('history.view')
        <div class="bg-white rounded-2xl border border-[#E0E0E0] py-4 px-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                    <!-- Nama Lahan Filter (Dynamic dari Firebase) -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Nama Lahan</label>
                        <div class="relative">
                            <button type="button" @click="dropdownNamaLahan = !dropdownNamaLahan" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary flex items-center justify-between">
                                <span x-text="namaLahan === '' ? 'Pilih nama lahan' : namaLahan"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdownNamaLahan ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdownNamaLahan" x-transition @click.away="dropdownNamaLahan = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                <button type="button" @click="namaLahan = ''; dropdownNamaLahan = false;" 
                                    class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">
                                    Semua Lahan
                                </button>
                                <template x-for="blok in availableBlocks" :key="blok">
                                    <button type="button" @click="namaLahan = blok; dropdownNamaLahan = false;" 
                                        class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50"
                                        x-text="blok">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Status Irigasi Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Status Irigasi</label>
                        <div class="relative">
                            <button type="button" @click="dropdownStatusIrigasi = !dropdownStatusIrigasi" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary flex items-center justify-between">
                                <span x-text="statusIrigasi === '' ? 'Pilih status' : statusIrigasi"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdownStatusIrigasi ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdownStatusIrigasi" x-transition @click.away="dropdownStatusIrigasi = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="statusIrigasi = ''; dropdownStatusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Status</button>
                                <button type="button" @click="statusIrigasi = 'Selesai'; dropdownStatusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Selesai</button>
                                <button type="button" @click="statusIrigasi = 'Aktif'; dropdownStatusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Aktif</button>
                                <button type="button" @click="statusIrigasi = 'Gagal'; dropdownStatusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Gagal</button>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Irigasi Filter (UI Only - Not Functional Yet) -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Jenis Irigasi</label>
                        <div class="relative">
                            <button type="button" @click="dropdownJenisIrigasi = !dropdownJenisIrigasi" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary flex items-center justify-between">
                                <span x-text="jenisIrigasi === '' ? 'Pilih jenis irigasi' : jenisIrigasi"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdownJenisIrigasi ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdownJenisIrigasi" x-transition @click.away="dropdownJenisIrigasi = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="jenisIrigasi = ''; dropdownJenisIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Jenis</button>
                                <button type="button" @click="jenisIrigasi = 'Otomatis'; dropdownJenisIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Otomatis</button>
                                <button type="button" @click="jenisIrigasi = 'Manual'; dropdownJenisIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Manual</button>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Tanggal</label>
                        <input 
                            type="date" 
                            x-model="tanggalFilter"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            style="color-scheme: light;"
                        />
                    </div>

                    <!-- Tombol Reset -->
                    <div class="flex flex-col gap-2 justify-end">
                        <button type="button" @click="reset()" class="h-11 px-6 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-100 transition-colors flex items-center gap-2">
                            <img src="/assets/icons/reset.svg" alt="Reset" class="w-4 h-4"> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <!-- Loading State -->
        <div x-show="isLoading" class="text-center py-8 bg-white rounded-2xl border border-gray-200 mb-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary mb-3"></div>
            <p class="text-gray-500 text-sm">Memuat data riwayat irigasi...</p>
        </div>

        <!-- Card Riwayat Irigasi dari Firebase -->
        @can('history.view')
        <div class="flex flex-col gap-4" x-show="!isLoading">
            <template x-for="card in filteredCards" :key="card.id">
                <div class="bg-white rounded-2xl border border-gray-200 py-5 px-6 flex flex-col gap-3 relative"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="flex flex-row justify-between items-start mb-1">
                        <!-- Kiri: Status dan Nama Lahan -->
                        <div class="flex flex-col gap-1">
                            <!-- Badge Status Irigasi -->
                            <span class="px-3 py-1 max-w-max rounded-lg text-xs font-semibold border-2 mb-1"
                                :class="{
                                    'bg-[#F2FDF5] text-[#16A34A] border-[#D3F3DF]': card.status_irigasi === 'Selesai',
                                    'bg-blue-50 text-blue-700 border-blue-400': card.status_irigasi === 'Aktif',
                                    'bg-red-50 text-red-600 border-red-400': card.status_irigasi === 'Gagal'
                                }"
                                x-text="'Irigasi ' + card.status_irigasi">
                            </span>
                            <div class="flex flex-row items-center gap-2">
                                <span class="text-lg font-bold text-title-card text-[#4F4F4F]" x-text="card.blok"></span>
                                <span class="mx-1 text-divider">•</span>
                                <span class="text-base text-text-green font-medium" x-text="card.sprayer"></span>
                            </div>
                        </div>
                        
                        <!-- Kanan: Badge Jenis Irigasi -->
                        <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border-2 border-blue-400">
                            Otomatis
                        </span>
                    </div>
                    <div class="flex flex-row flex-wrap gap-6 items-center text-base text-[#4F4F4F]">
                        <!-- Kelembaban Tanah -->
                        <div class="flex items-center gap-1.5">
                            <img src="/assets/icons/soil-temperature.svg" class="w-5 h-5" alt="Soil">
                            <span class="text-sm">Kelembaban Tanah: <span class="font-bold" 
                                :class="{
                                    'text-[#16A34A]': card.moisture_status === 'Basah' || card.moisture_status === 'Lembab',
                                    'text-yellow-600': card.moisture_status === 'Normal',
                                    'text-red-600': card.moisture_status === 'Kering'
                                }"
                                x-text="card.moisture_percent + '%'"></span></span>
                        </div>
                        <!-- Persentase -->
                        <div class="flex items-center gap-1.5">
                            <img src="/assets/icons/chart-line.svg" class="w-5 h-5" alt="Stat">
                            <span class="text-sm">Persentase: <span class="font-bold text-[#16A34A]" x-text="'+' + card.moisture_percent + '%'"></span></span>
                        </div>
                        <!-- Total Air -->
                        <div class="flex items-center gap-1.5" x-show="parseFloat(card.totalVolume_L) > 0">
                            <img src="/assets/icons/water.svg" class="w-5 h-5" alt="Water">
                            <span class="text-sm">Total Air: <span class="font-bold text-[#16A34A]" x-text="card.totalVolume_L + ' Liter'"></span></span>
                        </div>
                        <!-- Debit Air -->
                        <div class="flex items-center gap-1.5" x-show="parseFloat(card.flow_Lmin) > 0">
                            <img src="/assets/icons/wind-flow.svg" class="w-5 h-5" alt="Debit">
                            <span class="text-sm">Debit Air: <span class="font-bold text-[#16A34A]" x-text="card.flow_Lmin + ' Liter/menit'"></span></span>
                        </div>
                        <!-- Durasi (Hardcoded - data not in database yet) -->
                        <div class="flex items-center gap-1.5">
                            <img src="/assets/icons/clock.svg" class="w-5 h-5" alt="Duration" onerror="this.style.display='none'">
                            <span class="text-sm">Durasi: <span class="font-bold text-[#16A34A]">15:09 menit</span></span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-400 italic mt-1 text-right w-full" x-text="formatTimestamp(card.timestamp)"></div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredCards.length === 0 && !isLoading" 
                 class="text-center text-gray-400 py-8 bg-white rounded-2xl border border-gray-200">
                <template x-if="cards.length === 0">
                    <span>Tidak ada data riwayat irigasi dari Firebase.</span>
                </template>
                <template x-if="cards.length > 0">
                    <span>Tidak ada data riwayat irigasi yang sesuai filter.</span>
                </template>
            </div>
        </div>
        @endcan

        @cannot('history.view')
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-12 px-6 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-[#4F4F4F] mb-2">Akses Ditolak</h3>
            <p class="text-sm text-gray-500">Anda tidak memiliki izin untuk melihat riwayat irigasi.</p>
        </div>
        @endcannot
    </div>
@endsection

@push('scripts')
<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-database-compat.js"></script>

<script>
    // Firebase Configuration
    const firebaseConfig = {
        apiKey: "{{ config('firebase.api_key') }}",
        authDomain: "{{ config('firebase.auth_domain') }}",
        databaseURL: "{{ config('firebase.database_url') }}",
        projectId: "{{ config('firebase.project_id') }}",
        storageBucket: "{{ config('firebase.storage_bucket') }}",
        messagingSenderId: "{{ config('firebase.messaging_sender_id') }}",
        appId: "{{ config('firebase.app_id') }}"
    };

    // Initialize Firebase (only if not already initialized)
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }

    function irrigationHistory() {
        return {
            // Store cards data from Firebase
            cards: [],
            
            // Available blocks for filter dropdown (dari Firebase)
            availableBlocks: [],
            
            // Loading state
            isLoading: true,
            
            // Current date display
            currentDate: '',
            
            // Filter states
            namaLahan: '',
            statusIrigasi: '',
            jenisIrigasi: '',
            tanggalFilter: '',
            
            // Dropdown open states
            dropdownNamaLahan: false,
            dropdownStatusIrigasi: false,
            dropdownJenisIrigasi: false,
            
            // Computed filtered cards
            get filteredCards() {
                return this.cards.filter(card => {
                    const matchNamaLahan = this.namaLahan === '' || card.blok === this.namaLahan;
                    const matchStatusIrigasi = this.statusIrigasi === '' || card.status_irigasi === this.statusIrigasi;
                    // jenisIrigasi filter not functional yet - data not in database
                    const matchTanggal = this.tanggalFilter === '' || card.tanggal === this.tanggalFilter;
                    return matchNamaLahan && matchStatusIrigasi && matchTanggal;
                });
            },
            
            // Initialize component
            init() {
                // Set current date
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                this.currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
                
                // Load history from Firebase
                this.loadHistoryFromFirebase();
            },
            
            // Load history data from Firebase - path: MAOS -> Block -> Sprayer -> history -> tanggal -> unique_id -> data
            loadHistoryFromFirebase() {
                const db = firebase.database();
                const maosRef = db.ref('MAOS');
                
                maosRef.once('value')
                    .then((snapshot) => {
                        const maosData = snapshot.val();
                        
                        if (!maosData) {
                            this.isLoading = false;
                            return;
                        }
                        
                        const historyCards = [];
                        const blocks = new Set();
                        
                        // Loop through each Block in MAOS
                        Object.keys(maosData).forEach(blockName => {
                            const blockData = maosData[blockName];
                            if (!blockData || typeof blockData !== 'object') {
                                return;
                            }
                            
                            // Add block to available blocks for filter
                            blocks.add(blockName);
                            
                            // Loop through each Sprayer in Block
                            Object.keys(blockData).forEach(sprayerName => {
                                // Skip non-sprayer keys (like "control", "data", "info", etc)
                                if (!sprayerName.includes('Sprayer')) {
                                    return;
                                }
                                
                                const sprayerData = blockData[sprayerName];
                                if (!sprayerData || typeof sprayerData !== 'object') {
                                    return;
                                }
                                
                                // Check if sprayer has history
                                if (sprayerData.history && typeof sprayerData.history === 'object') {
                                    // Loop through each date in history (format: DD-MM-YYYY)
                                    Object.keys(sprayerData.history).forEach(dateKey => {
                                        const dateData = sprayerData.history[dateKey];
                                        if (!dateData || typeof dateData !== 'object') {
                                            return;
                                        }
                                        
                                        // Loop through each history entry (unique ID like -OkNNb3Axdpd0D0C9EYc)
                                        Object.keys(dateData).forEach(entryId => {
                                            const entry = dateData[entryId];
                                            if (!entry || typeof entry !== 'object') {
                                                return;
                                            }
                                            
                                            // Determine status irigasi based on relay or flow data
                                            let statusIrigasi = 'Selesai';
                                            const flowVal = parseFloat(entry.flow_Lmin || '0');
                                            const relayVal = entry.relay;
                                            
                                            if (relayVal === 1 || relayVal === '1' || flowVal > 0) {
                                                statusIrigasi = 'Aktif';
                                            } else if (entry.moisture_status === 'Kering' && flowVal === 0) {
                                                statusIrigasi = 'Selesai';
                                            }
                                            
                                            // Create card object
                                            historyCards.push({
                                                id: `${blockName}-${sprayerName}-${dateKey}-${entryId}`,
                                                blok: blockName,
                                                sprayer: sprayerName,
                                                tanggal: this.convertDateFormat(dateKey),
                                                tanggalDisplay: dateKey,
                                                status_irigasi: statusIrigasi,
                                                moisture_percent: entry.moisture_percent || '0',
                                                moisture_status: entry.moisture_status || 'Unknown',
                                                totalVolume_L: entry.totalVolume_L || '0.00',
                                                flow_Lmin: entry.flow_Lmin || '0.00',
                                                flow_mLs: entry.flow_mLs || '0.00',
                                                kecepatan_kmh: entry.kecepatan_kmh || '0.00',
                                                kecepatan_mps: entry.kecepatan_mps || '0.00',
                                                arah_angin: entry.arah_angin || '',
                                                timestamp: entry.timestamp || dateKey
                                            });
                                        });
                                    });
                                }
                            });
                        });
                        
                        // Sort by timestamp (newest first)
                        historyCards.sort((a, b) => {
                            return this.parseTimestamp(b.timestamp) - this.parseTimestamp(a.timestamp);
                        });
                        
                        this.cards = historyCards;
                        this.availableBlocks = Array.from(blocks).sort();
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            },
            
            // Convert date from DD-MM-YYYY to YYYY-MM-DD for filter comparison
            convertDateFormat(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            },
            
            // Parse timestamp string to Date object for sorting
            parseTimestamp(timestampStr) {
                if (!timestampStr) return 0;
                // Format: "DD-MM-YYYY HH:MM:SS"
                const parts = timestampStr.split(' ');
                if (parts.length < 1) return 0;
                
                const dateParts = parts[0].split('-');
                if (dateParts.length !== 3) return 0;
                
                const timeParts = parts[1] ? parts[1].split(':') : ['00', '00', '00'];
                
                const year = parseInt(dateParts[2]);
                const month = parseInt(dateParts[1]) - 1;
                const day = parseInt(dateParts[0]);
                const hour = parseInt(timeParts[0]) || 0;
                const minute = parseInt(timeParts[1]) || 0;
                const second = parseInt(timeParts[2]) || 0;
                
                return new Date(year, month, day, hour, minute, second).getTime();
            },
            
            // Format timestamp for display - Format: "27 Des 2025, 11:05 WIB"
            formatTimestamp(timestampStr) {
                if (!timestampStr) return '-';
                
                // Parse timestamp string (format: "DD-MM-YYYY HH:MM:SS")
                const parts = timestampStr.split(' ');
                if (parts.length < 2) return timestampStr;
                
                const dateParts = parts[0].split('-');
                const timeParts = parts[1].split(':');
                
                if (dateParts.length !== 3 || timeParts.length < 2) return timestampStr;
                
                const day = parseInt(dateParts[0]);
                const monthIndex = parseInt(dateParts[1]) - 1;
                const year = parseInt(dateParts[2]);
                const hour = timeParts[0];
                const minute = timeParts[1];
                
                // Month names in Indonesian (short form)
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const monthName = monthNames[monthIndex];
                
                // Format: "27 Des 2025, 11:05 WIB"
                return `${day} ${monthName} ${year}, ${hour}:${minute} WIB`;
            },
            
            // Reset all filters
            reset() {
                this.namaLahan = '';
                this.statusIrigasi = '';
                this.jenisIrigasi = '';
                this.tanggalFilter = '';
            }
        }
    }
</script>
@endpush
