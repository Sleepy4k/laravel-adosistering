@props([
    'statusSensor' => 'Semua',
    'statusPompa' => 'Semua',
    'searchQuery' => '',
    'role' => 'admin'
])

@php
    $createRoute = $role === 'superadmin' ? 'superadmin.users.create' : 'admin.users.create';
@endphp

<div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-6 mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <!-- Left side: Filters -->
        <div class="flex flex-col sm:flex-row gap-4 flex-1">
            <!-- Status Sensor Filter -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-[#4F4F4F]">Status Sensor</label>
                <div x-data="{ open: false }" class="relative">
                    <button 
                        @click="open = !open"
                        class="w-[200px] h-11 px-4 bg-white border border-gray-300 rounded-xl flex items-center justify-between text-sm text-gray-700 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                        <span x-text="selectedSensorStatus === '' ? 'Semua' : selectedSensorStatus"></span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div 
                        x-show="open" 
                        x-transition 
                        @click.away="open = false"
                        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg"
                        style="display: none;"
                    >
                        <button @click="selectedSensorStatus = ''; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-xl">Semua</button>
                        <button @click="selectedSensorStatus = 'Terhubung'; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Terhubung</button>
                        <button @click="selectedSensorStatus = 'Terputus'; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 last:rounded-b-xl">Terputus</button>
                    </div>
                </div>
            </div>

            <!-- Status Pompa Filter -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-medium text-[#4F4F4F]">Status Pompa</label>
                <div x-data="{ open: false }" class="relative">
                    <button 
                        @click="open = !open"
                        class="w-[200px] h-11 px-4 bg-white border border-gray-300 rounded-xl flex items-center justify-between text-sm text-gray-700 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                        <span x-text="selectedPumpStatus === '' ? 'Semua' : selectedPumpStatus"></span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div 
                        x-show="open" 
                        x-transition 
                        @click.away="open = false"
                        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg"
                        style="display: none;"
                    >
                        <button @click="selectedPumpStatus = ''; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-xl">Semua</button>
                        <button @click="selectedPumpStatus = 'Aktif'; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Aktif</button>
                        <button @click="selectedPumpStatus = 'Mati'; open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 last:rounded-b-xl">Mati</button>
                    </div>
                </div>
            </div>

            <!-- Search Box -->
            <div class="flex flex-col gap-2 flex-1">
                <label class="text-sm font-medium text-[#4F4F4F]">Cari data pengguna</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        placeholder="Cari nama pengguna atau nama IoT..."
                        x-model="searchTerm"
                        class="w-full h-11 pl-10 pr-4 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                </div>
            </div>
        </div>

        <!-- Right side: Add User Button -->
        <div class="flex flex-col gap-2">
            <label class="text-sm font-medium text-transparent">Action</label>
            <a href="{{ route($createRoute) }}" 
               class="bg-primary hover:bg-primary-hover text-white font-semibold py-2.5 px-6 rounded-xl transition-colors duration-200 flex items-center gap-2 whitespace-nowrap h-11">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pengguna
            </a>
        </div>
    </div>
</div>
