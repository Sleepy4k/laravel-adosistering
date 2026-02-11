@extends('layouts.dashboard')

@section('title', 'Riwayat Irigasi')

@section('content')
    {{-- Store Firebase module path for Alpine.js --}}
    <script>
        window.__FIREBASE_MODULE_PATH__ = "{{ Vite::asset('resources/js/firebase.js') }}";
        window.__FIREBASE_CONFIG__ = @json(config('firebase'));
    </script>

    {{-- irrigationHistory() is now loaded via resources/js/components/irrigation-history.js (through app.js) --}}

    <div class="w-full max-w-7xl mx-auto py-6" x-data="irrigationHistory()">
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
                @include('components.user.history-card')
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
