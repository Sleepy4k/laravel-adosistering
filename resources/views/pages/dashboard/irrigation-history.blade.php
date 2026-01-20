@extends('layouts.user')

@section('title', 'Riwayat Irigasi')

@section('content')
    <div class="w-full max-w-7xl mx-auto py-6" x-data="irrigationHistory()" x-init="init()">
        <!-- Header -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Riwayat Irigasi</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        @can('history.view')
        <div class="bg-white rounded-2xl border border-[#E0E0E0] py-4 px-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                    <!-- Nama Lahan Filter -->
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
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="namaLahan = ''; dropdownNamaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Lahan</button>
                                <button type="button" @click="namaLahan = 'Blok A'; dropdownNamaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Blok A</button>
                                <button type="button" @click="namaLahan = 'Blok B'; dropdownNamaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Blok B</button>
                                <button type="button" @click="namaLahan = 'Blok C'; dropdownNamaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Blok C</button>
                            </div>
                        </div>
                    </div>

                    <!-- Status Irigasi Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Status Irigasi</label>
                        <div class="relative">
                            <button type="button" @click="dropdownStatus = !dropdownStatus" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary flex items-center justify-between">
                                <span x-text="statusIrigasi === '' ? 'Pilih status irigasi' : statusIrigasi"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdownStatus ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdownStatus" x-transition @click.away="dropdownStatus = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="statusIrigasi = ''; dropdownStatus = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Status</button>
                                <button type="button" @click="statusIrigasi = 'Irigasi Selesai'; dropdownStatus = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Irigasi Selesai</button>
                                <button type="button" @click="statusIrigasi = 'Irigasi Aktif'; dropdownStatus = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Irigasi Aktif</button>
                                <button type="button" @click="statusIrigasi = 'Irigasi Gagal'; dropdownStatus = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Irigasi Gagal</button>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Irigasi Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Jenis Irigasi</label>
                        <div class="relative">
                            <button type="button" @click="dropdownJenis = !dropdownJenis" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary flex items-center justify-between">
                                <span x-text="jenisIrigasi === '' ? 'Pilih jenis irigasi' : jenisIrigasi"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdownJenis ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdownJenis" x-transition @click.away="dropdownJenis = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="jenisIrigasi = ''; dropdownJenis = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Jenis</button>
                                <button type="button" @click="jenisIrigasi = 'Otomatis'; dropdownJenis = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Otomatis</button>
                                <button type="button" @click="jenisIrigasi = 'Manual'; dropdownJenis = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Manual</button>
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

        <!-- Card Riwayat Irigasi -->
        @can('history.view')
        <div class="flex flex-col gap-4">
            @forelse($irrigationHistory as $card)
                <div x-show="shouldShowCard({{ json_encode($card) }})" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <x-user.irrigation-history-card
                        :blok="$card['blok']"
                        :status="$card['status']"
                        :jenis="$card['jenis']"
                        :sprayer="$card['sprayer']"
                        :kelembaban="$card['kelembaban']"
                        :persentase="$card['persentase'] ?? null"
                        :total_air="$card['total_air'] ?? null"
                        :debit_air="$card['debit_air'] ?? null"
                        :durasi="$card['durasi'] ?? null"
                        :waktu="$card['waktu']"
                    />
                </div>
            @empty
                <div class="text-center text-gray-400 py-8 bg-white rounded-2xl border border-gray-200">
                    Tidak ada data riwayat irigasi.
                </div>
            @endforelse

            <!-- Empty State untuk filter tidak ada hasil -->
            <div x-show="getVisibleCardsCount() === 0 && cards.length > 0" 
                 x-transition
                 class="text-center text-gray-400 py-8 bg-white rounded-2xl border border-gray-200">
                Tidak ada data riwayat irigasi yang sesuai filter.
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
<script>
    function irrigationHistory() {
        return {
            // Store cards data for filtering
            cards: @json($irrigationHistory ?? []),
            
            // Current date display
            currentDate: '',
            
            // Filter states
            namaLahan: '',
            statusIrigasi: '',
            jenisIrigasi: '',
            tanggalFilter: '',
            
            // Dropdown open states
            dropdownNamaLahan: false,
            dropdownStatus: false,
            dropdownJenis: false,
            
            // Initialize component
            init() {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                this.currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
                
                console.log('🌾 Irrigation History Loaded');
                console.log('📊 Total cards:', this.cards.length);
            },
            
            // Reset all filters
            reset() {
                this.namaLahan = '';
                this.statusIrigasi = '';
                this.jenisIrigasi = '';
                this.tanggalFilter = '';
                console.log('🔄 Filters reset');
            },
            
            // Check if a card should be shown based on filters
            shouldShowCard(card) {
                const matchNamaLahan = this.namaLahan === '' || card.blok === this.namaLahan;
                const matchStatus = this.statusIrigasi === '' || card.status === this.statusIrigasi;
                const matchJenis = this.jenisIrigasi === '' || card.jenis === this.jenisIrigasi;
                const matchTanggal = this.tanggalFilter === '' || card.tanggal === this.tanggalFilter;
                
                return matchNamaLahan && matchStatus && matchJenis && matchTanggal;
            },
            
            // Count visible cards for empty state
            getVisibleCardsCount() {
                return this.cards.filter(card => this.shouldShowCard(card)).length;
            }
        }
    }
</script>
@endpush
