@extends('layouts.dashboard')

@section('title', 'Notifikasi')

@section('content')
    <div class="w-full max-w-7xl mx-auto py-6" x-data="notificationPage()" x-init="init()">
        <!-- Header -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Notifikasi</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                    <!-- Jenis Notifikasi Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Jenis Notifikasi</label>
                        <div class="relative">
                            <button type="button" @click="dropdowns.jenisNotifikasi = !dropdowns.jenisNotifikasi" 
                                class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                                :class="dropdowns.jenisNotifikasi ? 'border-primary' : ''">
                                <span x-text="filters.jenisNotifikasi === '' ? 'Semua' : filters.jenisNotifikasi"></span>
                                <svg class="w-4 h-4 transition-transform" :class="dropdowns.jenisNotifikasi ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="dropdowns.jenisNotifikasi" x-transition @click.away="dropdowns.jenisNotifikasi = false" 
                                class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg">
                                <button type="button" @click="filters.jenisNotifikasi = ''; dropdowns.jenisNotifikasi = false;" 
                                    class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-t-xl">Semua</button>
                                <button type="button" @click="filters.jenisNotifikasi = 'Kendala Teknis'; dropdowns.jenisNotifikasi = false;" 
                                    class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kendala Teknis</button>
                                <button type="button" @click="filters.jenisNotifikasi = 'Kondisi Lahan'; dropdowns.jenisNotifikasi = false;" 
                                    class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kondisi Lahan</button>
                                <button type="button" @click="filters.jenisNotifikasi = 'Irigasi'; dropdowns.jenisNotifikasi = false;" 
                                    class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 rounded-b-xl">Irigasi</button>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Filter -->
                    <div class="flex flex-col gap-2 flex-1 min-w-0">
                        <label class="text-sm font-medium text-[#4F4F4F]">Tanggal</label>
                        <input 
                            type="date" 
                            x-model="filters.tanggal"
                            placeholder="Pilih rentang tanggal"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            style="color-scheme: light;"
                        />
                    </div>

                    <!-- Tombol Reset -->
                    <div class="flex flex-col gap-2 justify-end">
                        <button type="button" @click="resetFilters()" class="h-11 px-6 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-100 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Cards -->
        <div class="flex flex-col gap-4">
            <template x-for="notification in getFilteredNotifications()" :key="notification.id">
                <div class="bg-white rounded-2xl border border-gray-200 py-5 px-6 flex flex-col gap-3">
                    <!-- Badge Jenis Notifikasi -->
                    <span class="px-3 py-1 max-w-max rounded-lg text-xs font-semibold border"
                        :class="{
                            'bg-[#FEF2F2] text-[#DC2626] border-[#FEE2E2]': notification.jenis === 'Kendala Teknis',
                            'bg-[#FFFBEB] text-[#C47E09] border-[#FDECCE]': notification.jenis === 'Kondisi Lahan',
                            'bg-[#EFF6FF] text-[#2563EB] border-[#BFDBFE]': notification.jenis === 'Irigasi'
                        }"
                        x-text="notification.jenis">
                    </span>
                    
                    <!-- Nama Blok dan Sprayer -->
                    <div class="flex flex-row items-center gap-2">
                        <span class="text-lg font-bold text-[#4F4F4F]" x-text="notification.blok"></span>
                        <template x-if="notification.sprayer">
                            <span class="flex items-center gap-2">
                                <span class="text-gray-300">•</span>
                                <span class="text-base text-primary font-medium" x-text="notification.sprayer"></span>
                            </span>
                        </template>
                    </div>
                    
                    <!-- Pesan Notifikasi -->
                    <p class="text-sm text-[#4F4F4F] leading-relaxed" x-text="notification.pesan"></p>
                    
                    <!-- Waktu - Bawah Kanan -->
                    <div class="flex justify-end">
                        <span class="text-sm text-gray-400 italic" x-text="notification.waktu"></span>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="getFilteredNotifications().length === 0">
                <div class="text-center text-gray-400 py-8 bg-white rounded-2xl border border-gray-200">
                    Tidak ada notifikasi yang sesuai filter.
                </div>
            </template>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function notificationPage() {
        return {
            // Data notifications from backend
            notifications: @json($notifications ?? []),
            
            // Current date display
            currentDate: '',
            
            // Filter states
            filters: {
                jenisNotifikasi: '',
                tanggal: ''
            },
            
            // Dropdown open states
            dropdowns: {
                jenisNotifikasi: false
            },
            
            // Initialize component
            init() {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                this.currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            },
            
            // Get filtered notifications
            getFilteredNotifications() {
                return this.notifications.filter(notification => {
                    const matchJenis = this.filters.jenisNotifikasi === '' || notification.jenis === this.filters.jenisNotifikasi;
                    const matchTanggal = this.filters.tanggal === '' || notification.tanggal === this.filters.tanggal;
                    return matchJenis && matchTanggal;
                });
            },
            
            // Reset all filters
            resetFilters() {
                this.filters.jenisNotifikasi = '';
                this.filters.tanggal = '';
            }
        }
    }
</script>
@endpush
