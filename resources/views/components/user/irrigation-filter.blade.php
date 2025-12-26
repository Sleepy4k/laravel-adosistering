
<div class="bg-white rounded-2xl border border-[#E0E0E0] py-4 px-6 mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
            <!-- Nama Lahan Filter (Dropdown Custom, warna iot-filter) -->
            <div class="flex flex-col gap-2 flex-1 min-w-0">
                <label class="text-sm font-medium text-[#4F4F4F]">Nama Lahan</label>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" :class="open ? 'border-primary' : ''">
                        <span x-text="namaLahan === '' ? 'Cari nama lahan' : namaLahan"></span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg" style="display: none;">
                        <button @click="namaLahan = ''; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 first:rounded-t-xl">Cari nama lahan</button>
                        <button @click="namaLahan = 'Blok A'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Blok A</button>
                        <button @click="namaLahan = 'Blok B'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Blok B</button>
                        <button @click="namaLahan = 'Blok C'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl">Blok C</button>
                    </div>
                </div>
            </div>
            <!-- Status Irigasi Filter (Dropdown Custom, warna iot-filter) -->
            <div class="flex flex-col gap-2 flex-1 min-w-0">
                <label class="text-sm font-medium text-[#4F4F4F]">Status Irigasi</label>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" :class="open ? 'border-primary' : ''">
                        <span x-text="statusIrigasi === '' ? 'Pilih status irigasi' : statusIrigasi"></span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg" style="display: none;">
                        <button @click="statusIrigasi = ''; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 first:rounded-t-xl">Pilih status irigasi</button>
                        <button @click="statusIrigasi = 'Irigasi Selesai'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Irigasi Selesai</button>
                        <button @click="statusIrigasi = 'Irigasi Aktif'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Irigasi Aktif</button>
                        <button @click="statusIrigasi = 'Irigasi Gagal'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl">Irigasi Gagal</button>
                    </div>
                </div>
            </div>
            <!-- Jenis Irigasi Filter (Dropdown Custom, warna iot-filter) -->
            <div class="flex flex-col gap-2 flex-1 min-w-0">
                <label class="text-sm font-medium text-[#4F4F4F]">Jenis Irigasi</label>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" :class="open ? 'border-primary' : ''">
                        <span x-text="jenisIrigasi === '' ? 'Pilih jenis irigasi' : jenisIrigasi"></span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg" style="display: none;">
                        <button @click="jenisIrigasi = ''; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 first:rounded-t-xl">Pilih jenis irigasi</button>
                        <button @click="jenisIrigasi = 'Otomatis'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Otomatis</button>
                        <button @click="jenisIrigasi = 'Manual'; open = false;" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl">Manual</button>
                    </div>
                </div>
            </div>
            <!-- Tanggal Filter (Input Date) -->
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
                <button class="h-11 px-6 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-100 transition-colors flex items-center gap-2" type="button" @click="reset()">
                    <img src="/assets/icons/reset.svg" alt="Reset" class="w-4 h-4"> Reset
                </button>
            </div>
        </div>
    </div>
</div>
