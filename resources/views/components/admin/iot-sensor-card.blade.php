@props([
    'id',
    'userName', 
    'location',
    'sensorStatus',
    'humidity',
    'flowRate',
    'volume',
    'pumpStatus',
    'lastUpdate',
    'isPumpOn' => false,
    'profileImage' => null
])

<div class="bg-white rounded-2xl p-6 border border-[#C2C2C2] iot-card" 
     data-sensor-status="{{ $sensorStatus }}" 
     data-pump-status="{{ $pumpStatus }}"
     data-user-name="{{ $userName }}">
     
    {{-- Header: Avatar, User Name, Location --}}
    <div class="flex items-start gap-3 mb-4">
        <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden shrink-0">
            <img 
                src="{{ $profileImage ?? asset('assets/images/default-avatar.jpg') }}" 
                alt="{{ $userName }}"
                loading="lazy"
                class="w-full h-full object-cover"
                onerror="this.src='{{ asset('assets/images/default-avatar.jpg') }}'"
            >
        </div>
        <div class="flex-1">
            <p class="text-lg font-bold text-primary-darker mb-1">{{ $userName }}</p>
            <p class="text-sm text-[#808080]">{{ $location }}</p>
        </div>
        {{-- Options Menu (3 dots) --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-primary-darker" fill="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="5" r="2"></circle>
                    <circle cx="12" cy="12" r="2"></circle>
                    <circle cx="12" cy="19" r="2"></circle>
                </svg>
            </button>
            {{-- Dropdown Menu --}}
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-1 w-36 bg-white border border-gray-200 rounded-xl shadow-lg z-10"
                style="display: none;">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-xl">Detail</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 last:rounded-b-xl">Hapus</a>
            </div>
        </div>
    </div>

    {{-- Sensor Data Section --}}
    <div class="mb-4">
        <div class="flex items-center justify-between mb-3">
            <p class="text-base font-semibold text-[#4F4F4F]">Sensor Data</p>
            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $sensorStatus === 'Terhubung' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-red-50 text-red-700' }}">
                {{ $sensorStatus }}
            </span>
        </div>

        <div class="border-t border-[#C2C2C2] pt-3">
            {{-- Kelembaban Tanah --}}
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-[#4F4F4F]">Kelembaban Tanah</span>
                <span class="text-sm font-semibold text-text-green">{{ $humidity }}</span>
            </div>
            
            {{-- Debit Air Rata-Rata --}}
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-[#4F4F4F]">Debit Air Rata-Rata</span>
                <span class="text-sm font-semibold text-text-green">{{ $flowRate }}</span>
            </div>
            
            {{-- Total Volume Air --}}
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-[#4F4F4F]">Total Volume Air</span>
                <span class="text-sm font-semibold text-text-green">{{ $volume }}</span>
            </div>
            
            {{-- Pompa --}}
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-[#4F4F4F]">Pompa</span>
                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $pumpStatus === 'Aktif' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-red-50 text-red-700' }}">
                    {{ $pumpStatus }}
                </span>
            </div>
        </div>
    </div>

    {{-- Footer: Last Update --}}
    <div class="text-right">
        <p class="text-xs text-[#808080] italic">Terakhir update {{ $lastUpdate }}</p>
    </div>
</div>
