@extends('layouts.user')

@section('content')
    <div class="w-full max-w-7xl mx-auto py-6" x-data="{
        filters: {
            namaLahan: '',
            statusIrigasi: '',
            jenisIrigasi: ''
        },
        cards: [{
                blok: 'Blok B',
                status: 'Irigasi Selesai',
                jenis: 'Otomatis',
                sprayer: 'Sprayer 1',
                kelembaban: '61,12%',
                persentase: '+13,23%',
                total_air: '216 Liter',
                debit_air: '74,52 Liter/menit',
                durasi: '15:09 menit',
                waktu: '21 Des 2025, 11:05 WIB',
            },
            {
                blok: 'Blok A',
                status: 'Irigasi Selesai',
                jenis: 'Manual',
                sprayer: 'Sprayer 2',
                kelembaban: '64,22%',
                persentase: '+22,23%',
                total_air: '210 Liter',
                debit_air: '70,00 Liter/menit',
                durasi: '14:30 menit',
                waktu: '21 Des 2025, 11:12 WIB',
            },
            {
                blok: 'Blok C',
                status: 'Irigasi Gagal',
                jenis: 'Otomatis',
                sprayer: 'Sprayer 3',
                kelembaban: '35,00%',
                waktu: '21 Des 2025, 10:55 WIB',
            },
            {
                blok: 'Blok B',
                status: 'Irigasi Aktif',
                jenis: 'Manual',
                sprayer: 'Sprayer 1',
                kelembaban: '47,89%',
                waktu: '21 Des 2025, 10:50 WIB',
            },
        ],
        get filteredCards() {
            return this.cards.filter(card => {
                const namaLahan = this.filters.namaLahan === '' || card.blok === this.filters.namaLahan;
                const statusIrigasi = this.filters.statusIrigasi === '' || card.status === this.filters.statusIrigasi;
                const jenisIrigasi = this.filters.jenisIrigasi === '' || card.jenis === this.filters.jenisIrigasi;
                return namaLahan && statusIrigasi && jenisIrigasi;
            });
        }
    }" @filter-updated.window="filters = $event.detail">
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Riwayat Irigasi</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const now = new Date();
                    currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>
        <!-- Filter Section -->
        <x-user.irrigation-filter />

        <!-- Card Riwayat Irigasi -->
        <div class="flex flex-col gap-4">
            <div
                x-show="(filters.namaLahan === '' || 'Blok B' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Selesai' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Otomatis' === filters.jenisIrigasi)">
                <x-user.irrigation-history-card blok="Blok B" status="Irigasi Selesai" jenis="Otomatis" sprayer="Sprayer 1"
                    kelembaban="61,12%" persentase="+13,23%" total_air="216 Liter" debit_air="74,52 Liter/menit"
                    durasi="15:09 menit" waktu="21 Des 2025, 11:05 WIB" />
            </div>
            <div
                x-show="(filters.namaLahan === '' || 'Blok A' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Selesai' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Manual' === filters.jenisIrigasi)">
                <x-user.irrigation-history-card blok="Blok A" status="Irigasi Selesai" jenis="Manual" sprayer="Sprayer 2"
                    kelembaban="64,22%" persentase="+22,23%" total_air="210 Liter" debit_air="70,00 Liter/menit"
                    durasi="14:30 menit" waktu="21 Des 2025, 11:12 WIB" />
            </div>
            <div
                x-show="(filters.namaLahan === '' || 'Blok C' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Gagal' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Otomatis' === filters.jenisIrigasi)">
                <x-user.irrigation-history-card blok="Blok C" status="Irigasi Gagal" jenis="Otomatis" sprayer="Sprayer 3"
                    kelembaban="35,00%" waktu="21 Des 2025, 10:55 WIB" />
            </div>
            <div
                x-show="(filters.namaLahan === '' || 'Blok B' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Aktif' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Manual' === filters.jenisIrigasi)">
                <x-user.irrigation-history-card blok="Blok B" status="Irigasi Aktif" jenis="Manual" sprayer="Sprayer 1"
                    kelembaban="47,89%" waktu="21 Des 2025, 10:50 WIB" />
            </div>
            <template
                x-if="
            !((filters.namaLahan === '' || 'Blok B' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Selesai' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Otomatis' === filters.jenisIrigasi)) &&
            !((filters.namaLahan === '' || 'Blok A' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Selesai' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Manual' === filters.jenisIrigasi)) &&
            !((filters.namaLahan === '' || 'Blok C' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Gagal' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Otomatis' === filters.jenisIrigasi)) &&
            !((filters.namaLahan === '' || 'Blok B' === filters.namaLahan) && (filters.statusIrigasi === '' || 'Irigasi Aktif' === filters.statusIrigasi) && (filters.jenisIrigasi === '' || 'Manual' === filters.jenisIrigasi))
        ">
                <div class="text-center text-gray-400 py-8">Tidak ada data riwayat irigasi yang sesuai filter.</div>
            </template>
        </div>
    </div>
@endsection
