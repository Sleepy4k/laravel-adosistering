@props([
    'jenis',
    'blok',
    'sprayer' => null,
    'pesan',
    'waktu'
])

@php
    // Badge colors based on notification type
    $badgeClasses = match($jenis) {
        'Kendala Teknis' => 'bg-red-50 text-red-600 border-red-400',
        'Kondisi Lahan' => 'bg-orange-50 text-orange-600 border-orange-400',
        'Irigasi' => 'bg-green-50 text-green-600 border-green-400',
+        default => 'bg-gray-50 text-gray-600 border-gray-400',
    };
@endphp

<div class="bg-white rounded-2xl border border-gray-200 py-5 px-6 flex flex-col gap-3">
    <!-- Badge Jenis Notifikasi -->
    <span class="px-3 py-1 max-w-max rounded-lg text-xs font-semibold border {{ $badgeClasses }}">
        {{ $jenis }}
    </span>
    
    <!-- Nama Blok dan Sprayer -->
    <div class="flex flex-row items-center gap-2">
        <span class="text-lg font-bold text-[#4F4F4F]">{{ $blok }}</span>
        @if($sprayer)
        <span class="mx-1 text-gray-300">•</span>
        <span class="text-base text-primary font-medium">{{ $sprayer }}</span>
        @endif
    </div>
    
    <!-- Pesan Notifikasi -->
    <p class="text-sm text-[#4F4F4F] leading-relaxed">{{ $pesan }}</p>
    
    <!-- Waktu - Bawah Kanan -->
    <div class="flex justify-end">
        <span class="text-sm text-gray-400 italic">{{ $waktu }}</span>
    </div>
</div>
