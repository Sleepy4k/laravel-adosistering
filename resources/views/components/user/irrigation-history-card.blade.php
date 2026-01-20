
@props([
    'blok', 'status', 'jenis', 'sprayer', 'kelembaban', 'persentase' => null, 'total_air' => null, 'debit_air' => null, 'durasi' => null, 'waktu'
])
<div class="bg-white rounded-2xl border border-gray-200 py-5 px-6 flex flex-col gap-3 relative">
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
                <span class="text-lg font-bold text-title-card text-[#4F4F4F]">{{ $blok }}</span>
                <span class="mx-1 text-divider">•</span>
                <span class="text-base text-text-green font-medium">{{ $sprayer }}</span>
            </div>
        </div>
        <!-- Kanan: Jenis Irigasi -->
        <span class="text-xs font-semibold px-3 py-1 rounded-lg border absolute right-6 top-5 bg-blue-50 text-blue-600 border-blue-400">
            {{ ucfirst($jenis) }}
        </span>
    </div>
    <div class="flex flex-row flex-wrap gap-6 items-center text-base text-[#4F4F4F]">
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/soil-temperature.svg" class="w-5 h-5" alt="Soil">
            <span class="text-sm">Kelembaban Tanah: <span class="font-bold text-[#16A34A]">{{ $kelembaban }}</span></span>
        </div>
        @if($persentase)
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/chart-line.svg" class="w-5 h-5" alt="Stat">
            <span class="text-sm">Persentase: <span class="font-bold text-[#16A34A]">{{ $persentase }}</span></span>
        </div>
        @endif
        @if($total_air)
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/water.svg" class="w-5 h-5" alt="Water">
            <span class="text-sm">Total Air: <span class="font-bold text-[#16A34A]">{{ $total_air }}</span></span>
        </div>
        @endif
        @if($debit_air)
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/wind-flow.svg" class="w-5 h-5" alt="Debit">
            <span class="text-sm">Debit Air: <span class="font-bold text-[#16A34A]">{{ $debit_air }}</span></span>
        </div>
        @endif
        @if($durasi)
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/timer-sand.svg" class="w-5 h-5" alt="Timer">
            <span class="text-sm">Durasi: <span class="font-bold text-[#16A34A]">{{ $durasi }}</span></span>
        </div>
        @endif
    </div>
    <div class="text-sm text-gray-400 italic mt-1 text-right w-full">{{ $waktu }}</div>
</div>
