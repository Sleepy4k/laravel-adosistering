{{--
    Irrigation History Card — Alpine-aware (no server props)

    Must be used INSIDE an x-for loop that provides a `card` object with:
      - card.id                 (string)
      - card.blok               (string)
      - card.sprayer            (string)
      - card.status_irigasi     ("Selesai" | "Aktif" | "Gagal")
      - card.moisture_percent   (string)
      - card.moisture_status    (string)
      - card.totalVolume_L      (string)
      - card.flow_Lmin          (string)
      - card.timestamp          (string)

    Parent scope must provide:
      - formatTimestamp(timestampStr)
--}}

<div class="bg-white rounded-2xl border border-gray-200 py-5 px-6 flex flex-col gap-3 relative"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100">
    <div class="flex flex-row justify-between items-start mb-1">
        <!-- Kiri: Status dan Nama Lahan -->
        <div class="flex flex-col gap-1">
            <!-- Badge Status Irigasi -->
            <span class="px-3 py-1 max-w-max rounded-lg text-xs font-semibold border-2 mb-1"
                :class="{
                    'bg-[#F2FDF5] text-[#16A34A] border-[#D3F3DF]': card.status_irigasi === 'Selesai',
                    'bg-blue-50 text-blue-700 border-blue-400': card.status_irigasi === 'Aktif',
                    'bg-red-50 text-red-600 border-red-400': card.status_irigasi === 'Gagal'
                }"
                x-text="'Irigasi ' + card.status_irigasi">
            </span>
            <div class="flex flex-row items-center gap-2">
                <span class="text-lg font-bold text-title-card text-[#4F4F4F]" x-text="card.blok"></span>
                <span class="mx-1 text-divider">•</span>
                <span class="text-base text-text-green font-medium" x-text="card.sprayer"></span>
            </div>
        </div>

        <!-- Kanan: Badge Jenis Irigasi -->
        <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border-2 border-blue-400">
            Otomatis
        </span>
    </div>
    <div class="flex flex-row flex-wrap gap-6 items-center text-base text-[#4F4F4F]">
        <!-- Kelembaban Tanah -->
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/soil-temperature.svg" class="w-5 h-5" alt="Soil">
            <span class="text-sm">Kelembaban Tanah: <span class="font-bold"
                :class="{
                    'text-[#16A34A]': card.moisture_status === 'Basah' || card.moisture_status === 'Lembab',
                    'text-yellow-600': card.moisture_status === 'Normal',
                    'text-red-600': card.moisture_status === 'Kering'
                }"
                x-text="card.moisture_percent + '%'"></span></span>
        </div>
        <!-- Persentase -->
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/chart-line.svg" class="w-5 h-5" alt="Stat">
            <span class="text-sm">Persentase: <span class="font-bold text-[#16A34A]" x-text="'+' + card.moisture_percent + '%'"></span></span>
        </div>
        <!-- Total Air -->
        <div class="flex items-center gap-1.5" x-show="parseFloat(card.totalVolume_L) > 0">
            <img src="/assets/icons/water.svg" class="w-5 h-5" alt="Water">
            <span class="text-sm">Total Air: <span class="font-bold text-[#16A34A]" x-text="card.totalVolume_L + ' Liter'"></span></span>
        </div>
        <!-- Debit Air -->
        <div class="flex items-center gap-1.5" x-show="parseFloat(card.flow_Lmin) > 0">
            <img src="/assets/icons/wind-flow.svg" class="w-5 h-5" alt="Debit">
            <span class="text-sm">Debit Air: <span class="font-bold text-[#16A34A]" x-text="card.flow_Lmin + ' Liter/menit'"></span></span>
        </div>
        <!-- Durasi (Hardcoded - data not in database yet) -->
        <div class="flex items-center gap-1.5">
            <img src="/assets/icons/clock.svg" class="w-5 h-5" alt="Duration" onerror="this.style.display='none'">
            <span class="text-sm">Durasi: <span class="font-bold text-[#16A34A]">15:09 menit</span></span>
        </div>
    </div>
    <div class="text-sm text-gray-400 italic mt-1 text-right w-full" x-text="formatTimestamp(card.timestamp)"></div>
</div>
