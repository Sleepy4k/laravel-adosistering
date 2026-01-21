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
    {{-- Store chart module path and bloks data for Alpine.js --}}
    <script>
        window.__CHART_LOADER_PATH__ = "{{ Vite::asset('resources/js/chart-loader.js') }}";
        window.__BLOKS_DATA__ = @json($bloks);
        
        // Define statistikPage synchronously (not as module) so it's available immediately
        window.statistikPage = function() {
            return {
                chartsLoaded: false,
                
                async init() {
                    // Wait for DOM to be ready
                    await this.$nextTick();
                    
                    // Lazy load chart module
                    if (!this.chartsLoaded) {
                        try {
                            const chartLoaderPath = window.__CHART_LOADER_PATH__;
                            const bloksData = window.__BLOKS_DATA__;
                        
                            
                            // Import the module - this executes the module code
                            // which registers ChartModule on window
                            await import(chartLoaderPath);
                            
                            // After import, module should be available on window
                            const chartModule = window.ChartModule;
                        
                            
                            if (chartModule && typeof chartModule.initializeCharts === 'function') {
                                chartModule.initializeCharts(bloksData);
                                this.chartsLoaded = true;
                                
                            } else {
                                console.error('❌ initializeCharts function not found on window.ChartModule');
                            }
                        } catch (error) {
                            console.error('❌ Failed to load charts:', error);
                        }
                    }
                }
            }
        }
    </script>

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
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Total Penggunaan Air Keseluruhan -->
            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Total Penggunaan Air Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    {{ number_format($summary['total_penggunaan_air'], 2, ',', '.') }} 
                    <span class="text-lg font-normal">Liter</span>
                </p>
            </div>

            <!-- Kelembaban Rata Rata Keseluruhan -->
            <div class="bg-primary rounded-2xl py-6 px-6">
                <p class="text-white text-sm mb-2">Kelembaban Rata Rata Keseluruhan</p>
                <p class="text-white text-3xl font-bold">
                    {{ number_format($summary['kelembaban_rata_rata'], 2, ',', '.') }}%
                </p>
            </div>
        </div>

        <!-- Blok Sections -->
        @foreach($bloks as $index => $blok)
        <div class="bg-white rounded-2xl border border-[#C2C2C2] mb-6 overflow-hidden" x-data="{ expanded: {{ $index === 0 ? 'true' : 'false' }} }">
            <!-- Blok Header (Accordion Toggle) -->
            <button 
                @click="expanded = !expanded" 
                class="w-full py-4 px-6 flex items-center justify-between hover:bg-gray-50 transition-colors"
            >
                <h2 class="text-lg font-semibold text-[#4F4F4F]">{{ $blok['nama'] }}</h2>
                <svg 
                    class="w-5 h-5 text-gray-500 transition-transform duration-200" 
                    :class="{ 'rotate-180': expanded }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>

            <!-- Blok Content -->
            <div x-show="expanded" x-collapse>
                <div class="px-6 pb-6">
                    <!-- Statistik Cards -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Frekuensi Irigasi Aktif -->
                        <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                            <p class="text-sm text-gray-500 mb-3">Frekuensi Irigasi Aktif</p>
                            <p class="text-4xl font-bold text-[#467A30] mb-4">
                                {{ $blok['frekuensi_irigasi']['total'] ?? ($blok['frekuensi_irigasi']['otomatis'] + $blok['frekuensi_irigasi']['manual']) }}
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#BFDBFE] bg-[#EFF6FF] text-[#2563EB] text-xs font-medium">
                                    {{ $blok['frekuensi_irigasi']['otomatis'] }}x Otomatis
                                </span>
                                <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#E5E5E5] bg-[#FAFAFA] text-[#525252] text-xs font-medium">
                                    {{ $blok['frekuensi_irigasi']['manual'] }}x Manual
                                </span>
                            </div>
                        </div>

                        <!-- Kelembaban Rata Rata -->
                        <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                            <p class="text-sm text-gray-500 mb-3">Kelembaban Rata Rata</p>
                            <div class="flex items-center gap-2 mb-4">
                                <p class="text-4xl font-bold text-[#467A30]">
                                    {{ number_format($blok['kelembaban']['rata_rata'] ?? $blok['kelembaban_rata_rata'], 2, ',', '.') }}%
                                </p>
                                @php
                                    $status = $blok['kelembaban']['status'] ?? 'Lembab';
                                    $statusClass = match($status) {
                                        'Kering' => 'bg-red-50 text-red-600 border-red-200',
                                        'Basah' => 'bg-blue-50 text-blue-600 border-blue-200',
                                        'Lembab' => 'bg-[#FFFBEB] text-[#C47E09] border-[#FDECCE]',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-md border text-xs font-medium {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#FEE2E2] bg-[#FEF2F2] text-[#DC2626] text-xs font-medium">{{ $blok['kelembaban']['min'] ?? 31 }}%</span>
                                <span class="text-gray-400">-</span>
                                <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#D3F3DF] bg-[#F2FDF5] text-[#16A34A] text-xs font-medium">{{ $blok['kelembaban']['max'] ?? 52 }}%</span>
                            </div>
                        </div>

                        <!-- Total Air Keluar -->
                        <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                            <p class="text-sm text-gray-500 mb-3">Total Air Keluar</p>
                            <p class="text-4xl font-bold text-[#467A30] mb-4">
                                {{ number_format($blok['total_air_digunakan'], 2, ',', '.') }}
                            </p>
                            <p class="text-sm text-[#4F4F4F]">Liter</p>
                        </div>

                        <!-- Debit Air Rata Rata -->
                        <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                            <p class="text-sm text-gray-500 mb-3">Debit Air Rata Rata</p>
                            <p class="text-4xl font-bold text-[#467A30] mb-4">
                                {{ number_format($blok['debit_air_rata_rata'], 2, ',', '.') }}
                            </p>
                            <p class="text-sm text-[#4F4F4F]">Liter/menit</p>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Kelembaban Tanah Chart -->
                        <div class="bg-white border border-[#C2C2C2] rounded-xl p-4">
                            <h3 class="text-base font-semibold text-[#4F4F4F] mb-4">Kelembaban Tanah</h3>
                            <div class="chart-container">
                                <canvas id="chartKelembaban{{ $blok['id'] }}"></canvas>
                            </div>
                            <div class="flex items-center justify-center gap-2 mt-4">
                                <span class="w-4 h-4 border-3 border-[#67B744] bg-white flex items-center justify-center">
                                </span>
                                <span class="text-xs text-gray-500">Kelembaban Tanah</span>
                            </div>
                        </div>

                        <!-- Penggunaan Air Chart -->
                        <div class="bg-white border border-[#C2C2C2] rounded-xl p-4">
                            <h3 class="text-base font-semibold text-[#4F4F4F] mb-4">Penggunaan Air</h3>
                            <div class="chart-container">
                                <canvas id="chartPenggunaanAir{{ $blok['id'] }}"></canvas>
                            </div>
                            <div class="flex items-center justify-center gap-2 mt-4">
                                <span class="w-4 h-4 border-3 border-[#0F92F0] bg-white flex items-center justify-center">
                                </span>
                                <span class="text-xs text-gray-500">Penggunaan Air</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
