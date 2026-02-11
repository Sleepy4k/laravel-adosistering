{{--
    Statistik Block Accordion — Alpine-aware (no server props)

    Must be used INSIDE an x-for loop that provides a `blok` object with:
      - blok.id                       (number)
      - blok.nama                     (string)
      - blok.frekuensi_irigasi        (object: { total, otomatis, manual })
      - blok.kelembaban               (object: { rata_rata, min, max, status })
      - blok.total_air_digunakan      (number)
      - blok.debit_air_rata_rata      (number)
      - blok.chart_kelembaban         (array)
      - blok.chart_penggunaan_air     (array)

    Parent scope (statistikPage) must provide:
      - formatNumber(value, decimals)
      - getMoistureStatusClass(status)

    Also requires `index` from x-for to auto-expand first block.
--}}

<div class="bg-white rounded-2xl border border-[#C2C2C2] mb-6 overflow-hidden" x-data="{ expanded: index === 0 }">
    <!-- Blok Header (Accordion Toggle) -->
    <button
        @click="expanded = !expanded"
        class="w-full py-4 px-6 flex items-center justify-between hover:bg-gray-50 transition-colors"
    >
        <h2 class="text-lg font-semibold text-[#4F4F4F]" x-text="blok.nama"></h2>
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
                    <p class="text-4xl font-bold text-[#467A30] mb-4" x-text="blok.frekuensi_irigasi.total"></p>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#BFDBFE] bg-[#EFF6FF] text-[#2563EB] text-xs font-medium">
                            <span x-text="blok.frekuensi_irigasi.otomatis"></span>x Otomatis
                        </span>
                        <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#E5E5E5] bg-[#FAFAFA] text-[#525252] text-xs font-medium">
                            <span x-text="blok.frekuensi_irigasi.manual"></span>x Manual
                        </span>
                    </div>
                </div>

                <!-- Kelembaban Rata Rata -->
                <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                    <p class="text-sm text-gray-500 mb-3">Kelembaban Rata Rata</p>
                    <div class="flex items-center gap-2 mb-4">
                        <p class="text-4xl font-bold text-[#467A30]">
                            <span x-text="formatNumber(blok.kelembaban.rata_rata)"></span>%
                        </p>
                        <span class="inline-flex items-center px-2 py-1 rounded-md border text-xs font-medium"
                              :class="getMoistureStatusClass(blok.kelembaban.status)"
                              x-text="blok.kelembaban.status">
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#FEE2E2] bg-[#FEF2F2] text-[#DC2626] text-xs font-medium">
                            <span x-text="formatNumber(blok.kelembaban.min, 0)"></span>%
                        </span>
                        <span class="text-gray-400">-</span>
                        <span class="inline-flex items-center px-1.5 py-1 rounded-lg border border-[#D3F3DF] bg-[#F2FDF5] text-[#16A34A] text-xs font-medium">
                            <span x-text="formatNumber(blok.kelembaban.max, 0)"></span>%
                        </span>
                    </div>
                </div>

                <!-- Total Air Keluar -->
                <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                    <p class="text-sm text-gray-500 mb-3">Total Air Keluar</p>
                    <p class="text-4xl font-bold text-[#467A30] mb-4">
                        <span x-text="formatNumber(blok.total_air_digunakan)"></span>
                    </p>
                    <p class="text-sm text-[#4F4F4F]">Liter</p>
                </div>

                <!-- Debit Air Rata Rata -->
                <div class="bg-white border border-[#C2C2C2] rounded-xl py-4 px-4">
                    <p class="text-sm text-gray-500 mb-3">Debit Air Rata Rata</p>
                    <p class="text-4xl font-bold text-[#467A30] mb-4">
                        <span x-text="formatNumber(blok.debit_air_rata_rata)"></span>
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
                        <canvas :id="'chartKelembaban' + blok.id"></canvas>
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
                        <canvas :id="'chartPenggunaanAir' + blok.id"></canvas>
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
