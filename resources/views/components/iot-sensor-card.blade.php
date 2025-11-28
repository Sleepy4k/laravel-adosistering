<div class="bg-white rounded-2xl p-6 border border-[#C2C2C2]">
    {{-- Header: Avatar, Name, Location --}}
    <div class="flex items-start gap-3 mb-4">
        <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden shrink-0">
            <img 
                src="{{ $profileImage ?? asset('assets/images/default-avatar.png') }}" 
                alt="{{ $name }}"
                class="w-full h-full object-cover"
                onerror="this.src='{{ asset('assets/images/default-avatar.png') }}'"
            >
        </div>
        <div class="flex-1">
            <p class="text-xl font-bold text-primary-darker mb-0.5">{{ $name }}</p>
            <p class="text-base text-[#808080]">{{ $location }}</p>
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
                <span class="text-sm font-semibold text-primary-color">{{ $humidity }}</span>
            </div>
            
            {{-- Debit Air Rata-Rata --}}
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-[#4F4F4F]">Debit Air Rata-Rata</span>
                <span class="text-sm font-semibold text-primary-color">{{ $flowRate }}</span>
            </div>
            
            {{-- Total Volume Air --}}
            <div class="flex items-center justify-between py-2">
                <span class="text-sm text-[#4F4F4F]">Total Volume Air</span>
                <span class="text-sm font-semibold text-primary-color">{{ $volume }}</span>
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

    {{-- Footer: Last Update, Toggle, Checkbox --}}
    <div class="pt-4 border-t border-[#C2C2C2]">
        <p class="text-xs text-[#999999] italic text-right mb-4">Terakhir update {{ $lastUpdate }}</p>
        
        <div class="flex items-center justify-between mb-4">
            {{-- Toggle Switch with ON Label --}}
            <div class="flex items-center gap-2">
                <button 
                    type="button"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $isPumpOn ? 'bg-primary' : 'bg-gray-300' }}"
                    x-data="{ on: {{ $isPumpOn ? 'true' : 'false' }} }"
                    @click="on = !on"
                    :class="on ? 'bg-primary' : 'bg-gray-300'"
                >
                    <span 
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                        :class="on ? 'translate-x-6' : 'translate-x-1'"
                    ></span>
                </button>
                <span 
                    class="text-sm font-bold"
                    x-data="{ on: {{ $isPumpOn ? 'true' : 'false' }} }"
                    :class="on ? 'text-[#4F4F4F]' : 'text-[#999999]'"
                    x-text="on ? 'ON' : 'OFF'"
                >{{ $isPumpOn ? 'ON' : 'OFF' }}</span>
            </div>
            
            {{-- Irigasi Otomatis Checkbox --}}
            <label class="flex items-center gap-2 cursor-pointer">
                <span class="text-sm text-[#4F4F4F]">Irigasi Otomatis</span>
                <input 
                    type="checkbox" 
                    class="checkbox-green"
                    {{ $isAutoIrrigation ? 'checked' : '' }}
                >
            </label>
        </div>

        {{-- Lihat Detail Button --}}
        <a href="{{ route('user.iot-sensor.detail', ['id' => $id]) }}" 
           class="block w-full text-center py-2.5 px-4 bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg transition-colors">
            Lihat Detail & Peta
        </a>
    </div>
</div>
