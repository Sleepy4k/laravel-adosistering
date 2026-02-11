@extends('layouts.dashboard')

@section('title', 'Statistik')

@push('styles')
<style>
    /* Chart container styling */
    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
</style>
@endpush

@section('content')
    {{-- Store config paths for Alpine.js --}}
    <script>
        window.__CHART_LOADER_PATH__ = "{{ Vite::asset('resources/js/chart-loader.js') }}";
        window.__FIREBASE_MODULE_PATH__ = "{{ Vite::asset('resources/js/firebase.js') }}";
        window.__FIREBASE_CONFIG__ = @json(config('firebase'));
        window.__CURRENT_PERIOD__ = "{{ $currentPeriod }}";
    </script>

    {{-- statistikPage() is now loaded via resources/js/components/statistik-page.js (through app.js) --}}

    <div class="max-w-7xl mx-auto" x-data="statistikPage()">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Statistik</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const now = new Date();
                    currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" loading="lazy" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>

        <!-- Period Filter Tabs -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Period Tabs -->
                <div class="flex items-center justify-center gap-6">
                    <a href="{{ route('user.statistik', ['period' => 'today']) }}" 
                       class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === 'today' ? 'text-[#67B744] underline underline-offset-8 decoration-2 decoration-[#67B744]' : 'text-gray-500 hover:text-gray-700' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('user.statistik', ['period' => '7days']) }}" 
                       class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === '7days' ? 'text-[#67B744] underline underline-offset-8 decoration-2 decoration-[#67B744]' : 'text-gray-500 hover:text-gray-700' }}">
                        7 Hari Terakhir
                    </a>
                    <a href="{{ route('user.statistik', ['period' => '30days']) }}" 
                       class="relative px-4 py-2 text-sm font-medium transition-colors {{ $currentPeriod === '30days' ? 'text-[#67B744] underline underline-offset-8 decoration-2 decoration-[#67B744]' : 'text-gray-500 hover:text-gray-700' }}">
                        30 Hari Terakhir
                    </a>
                </div>
                
                <!-- Data Mode Toggle (Raw / Smoothed) -->
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Mode Data:</span>
                    
                    <!-- Toggle Switch Style Button -->
                    <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-xl">
                        <!-- Raw Button -->
                        <button 
                            type="button"
                            x-on:click="if(useSmoothing) { toggleSmoothing(); }"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer"
                            :class="!useSmoothing 
                                ? 'bg-white text-[#67B744] shadow-sm border border-[#67B744]' 
                                : 'bg-transparent text-gray-500 hover:text-gray-700'"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Data Mentah
                            </span>
                        </button>
                        
                        <!-- Smoothed Button -->
                        <button 
                            type="button"
                            x-on:click="if(!useSmoothing && !noNumericHistory) { toggleSmoothing(); }"
                            :disabled="noNumericHistory"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                            :class="[
                                noNumericHistory 
                                    ? 'bg-gray-200 text-gray-400 cursor-not-allowed' 
                                    : useSmoothing 
                                        ? 'bg-[#67B744] text-white shadow-sm cursor-pointer' 
                                        : 'bg-transparent text-gray-500 hover:text-gray-700 cursor-pointer'
                            ]"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                                Smoothing
                            </span>
                        </button>
                    </div>
                    
                    <!-- Loading indicator -->
                    <div x-show="isSmoothingLoading" class="flex items-center" style="display: none;">
                        <svg class="animate-spin h-5 w-5 text-[#67B744]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <!-- Warning if no numeric history (MySQL backend not ready) -->
                    <div x-show="noNumericHistory" 
                         class="flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-200 rounded-lg"
                         style="display: none;">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-xs text-blue-700">Realtime data only</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="isLoading" class="text-center py-12 bg-white rounded-2xl border border-gray-200 mb-6">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-primary mb-4"></div>
            <p class="text-gray-500 text-sm">Memuat data statistik dari Firebase...</p>
        </div>

        <!-- Summary Cards -->
        <div x-show="!isLoading" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Total Penggunaan Air Keseluruhan -->
            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Total Penggunaan Air Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    <span x-text="formatNumber(summary.total_penggunaan_air)"></span>
                    <span class="text-lg font-normal">Liter</span>
                </p>
            </div>

            <!-- Kelembaban Rata Rata Keseluruhan -->
            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Kelembaban Rata Rata Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    <span x-text="formatNumber(summary.kelembaban_rata_rata)"></span>%
                </p>
            </div>
        </div>

        <!-- Blok Sections (Dynamic from Firebase) -->
        <template x-for="(blok, index) in bloks" :key="blok.id">
            @include('components.user.statistik-block')
        </template>

        <!-- Empty State -->
        <div x-show="!isLoading && bloks.length === 0" class="text-center py-12 bg-white rounded-2xl border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-[#4F4F4F] mb-2">Tidak Ada Data</h3>
            <p class="text-sm text-gray-500">Data statistik tidak tersedia dari Firebase.</p>
        </div>
    </div>
@endsection
