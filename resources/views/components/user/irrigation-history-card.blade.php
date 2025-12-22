
@props([
    'blok', 'status', 'jenis', 'sprayer', 'kelembaban', 'persentase' => null, 'total_air' => null, 'debit_air' => null, 'durasi' => null, 'waktu'
])
<div class="bg-white rounded-2xl border border-gray-200 py-4 px-6 flex flex-col gap-2 relative">
    <div class="flex flex-row justify-between items-start mb-1">
        <!-- Kiri: Status dan Nama Lahan -->
        <div class="flex flex-col gap-1">
            <!-- Badge Status -->
            <span class="px-3 py-1 max-w-max rounded-lg text-xs font-semibold border-2 mb-1
                @if($status==='Irigasi Selesai') bg-[#F2FDF5] text-[#16A34A] border-[#D3F3DF]
                @elseif($status==='Irigasi Aktif') bg-yellow-50 text-yellow-700 border-yellow-400
                @else bg-red-50 text-red-600 border-red-400 @endif">
                {{ $status }}
            </span>
            <div class="flex flex-row items-center gap-2">
                <span class="text-base font-bold text-title-card text-[#4F4F4F]">{{ $blok }}</span>
                <span class="mx-1 text-divider">•</span>
                <span class="text-sm text-green-700 font-medium">{{ $sprayer }}</span>
            </div>
        </div>
        <!-- Kanan: Jenis Irigasi -->
        <span class="text-xs font-semibold px-3 py-1 rounded-lg border absolute right-6 top-4 bg-blue-50 text-blue-600 border-blue-400">
            {{ ucfirst($jenis) }}
        </span>
    </div>
    <div class="flex flex-row flex-wrap gap-6 items-center text-sm text-title-card">
        <div class="flex items-center gap-1">
            <img src="/assets/icons/soil-temperature.svg" class="w-4 h-4" alt="Soil">
            <span>Kelembaban Tanah: <span class="font-bold text-green-700">{{ $kelembaban }}</span></span>
        </div>
        @if($persentase)
        <div class="flex items-center gap-1">
            <img src="/assets/icons/chart-line.svg" class="w-4 h-4" alt="Stat">
            <span>Persentase: <span class="font-bold text-green-700">{{ $persentase }}</span></span>
        </div>
        @endif
        @if($total_air)
        <div class="flex items-center gap-1">
            <img src="/assets/icons/water.svg" class="w-4 h-4" alt="Water">
            <span>Total Air: <span class="font-bold text-green-700">{{ $total_air }}</span></span>
        </div>
        <div class="flex items-center gap-1">
            <img src="/assets/icons/wind-flow.svg" class="w-4 h-4" alt="Debit">
            <span>Debit Air: <span class="font-bold text-green-700">{{ $debit_air }}</span></span>
        </div>
        <div class="flex items-center gap-1">
            <img src="/assets/icons/timer-sand.svg" class="w-4 h-4" alt="Timer">
            <span>Durasi: <span class="font-bold text-green-700">{{ $durasi }}</span></span>
        </div>
        @endif
    </div>
    <div class="text-xs text-gray-400 italic mt-1 text-right w-full">{{ $waktu }}</div>
</div>
