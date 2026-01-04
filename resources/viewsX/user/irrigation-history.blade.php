@extends('layouts.user')

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
        <div class="bg-white rounded-2xl border border-[#E0E0E0] py-4 px-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                    <!-- Nama Lahan Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Nama Lahan</label>
                        <div class="relative">
                            <button type="button" @click="dropdowns.namaLahan = !dropdowns.namaLahan" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                                :class="dropdowns.namaLahan ? 'border-primary' : ''">
                                <span x-text="filters.namaLahan === '' ? 'Cari nama lahan' : filters.namaLahan"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdowns.namaLahan ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdowns.namaLahan" x-transition @click.away="dropdowns.namaLahan = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="filters.namaLahan = ''; dropdowns.namaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Lahan</button>
                                <button type="button" @click="filters.namaLahan = 'Blok A'; dropdowns.namaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Blok A</button>
                                <button type="button" @click="filters.namaLahan = 'Blok B'; dropdowns.namaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Blok B</button>
                                <button type="button" @click="filters.namaLahan = 'Blok C'; dropdowns.namaLahan = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Blok C</button>
                            </div>
                        </div>
                    </div>

                    <!-- Status Irigasi Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Status Irigasi</label>
                        <div class="relative">
                            <button type="button" @click="dropdowns.statusIrigasi = !dropdowns.statusIrigasi" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                                :class="dropdowns.statusIrigasi ? 'border-primary' : ''">
                                <span x-text="filters.statusIrigasi === '' ? 'Pilih status irigasi' : filters.statusIrigasi"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdowns.statusIrigasi ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdowns.statusIrigasi" x-transition @click.away="dropdowns.statusIrigasi = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="filters.statusIrigasi = ''; dropdowns.statusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Status</button>
                                <button type="button" @click="filters.statusIrigasi = 'Irigasi Selesai'; dropdowns.statusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Irigasi Selesai</button>
                                <button type="button" @click="filters.statusIrigasi = 'Irigasi Aktif'; dropdowns.statusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Irigasi Aktif</button>
                                <button type="button" @click="filters.statusIrigasi = 'Irigasi Gagal'; dropdowns.statusIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Irigasi Gagal</button>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Irigasi Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Jenis Irigasi</label>
                        <div class="relative">
                            <button type="button" @click="dropdowns.jenisIrigasi = !dropdowns.jenisIrigasi" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                                :class="dropdowns.jenisIrigasi ? 'border-primary' : ''">
                                <span x-text="filters.jenisIrigasi === '' ? 'Pilih jenis irigasi' : filters.jenisIrigasi"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdowns.jenisIrigasi ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdowns.jenisIrigasi" x-transition @click.away="dropdowns.jenisIrigasi = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="filters.jenisIrigasi = ''; dropdowns.jenisIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua Jenis</button>
                                <button type="button" @click="filters.jenisIrigasi = 'Otomatis'; dropdowns.jenisIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Otomatis</button>
                                <button type="button" @click="filters.jenisIrigasi = 'Manual'; dropdowns.jenisIrigasi = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Manual</button>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Tanggal</label>
                        <input 
                            type="date" 
                            x-model="filters.tanggal"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            style="color-scheme: light;"
                        />
                    </div>

                    <!-- Tombol Reset -->
                    <div class="flex flex-col gap-2 justify-end">
                        <button type="button" @click="resetFilters()" class="h-11 px-6 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-100 transition-colors flex items-center gap-2">
                            <img src="/assets/icons/reset.svg" alt="Reset" class="w-4 h-4"> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Riwayat Irigasi -->
        <div class="flex flex-col gap-4">
            <template x-for="(card, index) in getFilteredCards()" :key="card.id">
                <div class="bg-white rounded-2xl border border-gray-200 py-5 px-6 flex flex-col gap-3 relative">
                    <div class="flex flex-row justify-between items-start mb-1">
                        <!-- Kiri: Status dan Nama Lahan -->
                        <div class="flex flex-col gap-1">
                            <!-- Badge Status -->
                            <span class="px-3 py-1 max-w-max rounded-lg text-xs font-semibold border-2 mb-1"
                                :class="{
                                    'bg-[#F2FDF5] text-[#16A34A] border-[#D3F3DF]': card.status === 'Irigasi Selesai',
                                    'bg-yellow-50 text-yellow-700 border-yellow-400': card.status === 'Irigasi Aktif',
                                    'bg-red-50 text-red-600 border-red-400': card.status === 'Irigasi Gagal'
                                }"
                                x-text="card.status">
                            </span>
                            <div class="flex flex-row items-center gap-2">
                                <span class="text-lg font-bold text-[#4F4F4F]" x-text="card.blok"></span>
                                <span class="mx-1 text-gray-300">•</span>
                                <span class="text-base text-[#16A34A] font-medium" x-text="card.sprayer"></span>
                            </div>
                        </div>
                        <!-- Kanan: Jenis Irigasi -->
                        <span class="text-xs font-semibold px-3 py-1 rounded-lg border absolute right-6 top-5 bg-blue-50 text-blue-600 border-blue-400" x-text="card.jenis"></span>
                    </div>
                    <div class="flex flex-row flex-wrap gap-6 items-center text-base text-[#4F4F4F]">
                        <div class="flex items-center gap-1.5">
                            <img src="/assets/icons/soil-temperature.svg" class="w-5 h-5" alt="Soil">
                            <span>Kelembaban Tanah: <span class="font-bold text-[#16A34A]" x-text="card.kelembaban"></span></span>
                        </div>
                        <template x-if="card.persentase">
                            <div class="flex items-center gap-1.5">
                                <img src="/assets/icons/chart-line.svg" class="w-5 h-5" alt="Stat">
                                <span>Persentase: <span class="font-bold text-[#16A34A]" x-text="card.persentase"></span></span>
                            </div>
                        </template>
                        <template x-if="card.total_air">
                            <div class="flex items-center gap-1.5">
                                <img src="/assets/icons/water.svg" class="w-5 h-5" alt="Water">
                                <span>Total Air: <span class="font-bold text-[#16A34A]" x-text="card.total_air"></span></span>
                            </div>
                        </template>
                        <template x-if="card.debit_air">
                            <div class="flex items-center gap-1.5">
                                <img src="/assets/icons/wind-flow.svg" class="w-5 h-5" alt="Debit">
                                <span>Debit Air: <span class="font-bold text-[#16A34A]" x-text="card.debit_air"></span></span>
                            </div>
                        </template>
                        <template x-if="card.durasi">
                            <div class="flex items-center gap-1.5">
                                <img src="/assets/icons/timer-sand.svg" class="w-5 h-5" alt="Timer">
                                <span>Durasi: <span class="font-bold text-[#16A34A]" x-text="card.durasi"></span></span>
                            </div>
                        </template>
                    </div>
                    <div class="text-sm text-gray-400 italic mt-1 text-right w-full" x-text="card.waktu"></div>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="getFilteredCards().length === 0">
                <div class="text-center text-gray-400 py-8 bg-white rounded-2xl border border-gray-200">
                    Tidak ada data riwayat irigasi yang sesuai filter.
                </div>
            </template>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function irrigationHistory() {
        return {
            // Data cards dari backend
            cards: @json($irrigationHistory ?? []),
            
            // Current date display
            currentDate: '',
            
            // Filter states
            filters: {
                namaLahan: '',
                statusIrigasi: '',
                jenisIrigasi: '',
                tanggal: ''
            },
            
            // Dropdown open states
            dropdowns: {
                namaLahan: false,
                statusIrigasi: false,
                jenisIrigasi: false
            },
            
            // Initialize component
            init() {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                this.currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
                
                console.log('🔍 Irrigation History Loaded');
                console.log('📊 Total cards:', this.cards.length);
                console.log('📋 Cards data:', this.cards);
            },
            
            // Get filtered cards (method instead of getter for better compatibility)
            getFilteredCards() {
                return this.cards.filter(card => {
                    const matchNamaLahan = this.filters.namaLahan === '' || card.blok === this.filters.namaLahan;
                    const matchStatus = this.filters.statusIrigasi === '' || card.status === this.filters.statusIrigasi;
                    const matchJenis = this.filters.jenisIrigasi === '' || card.jenis === this.filters.jenisIrigasi;
                    const matchTanggal = this.filters.tanggal === '' || card.tanggal === this.filters.tanggal;
                    return matchNamaLahan && matchStatus && matchJenis && matchTanggal;
                });
            },
            
            // Reset all filters
            resetFilters() {
                this.filters.namaLahan = '';
                this.filters.statusIrigasi = '';
                this.filters.jenisIrigasi = '';
                this.filters.tanggal = '';
            }
        }
    }
</script>
@endpush
