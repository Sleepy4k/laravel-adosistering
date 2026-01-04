@extends('layouts.user')

@section('title', 'Pengaturan')

@section('content')
    <div class="w-full max-w-7xl mx-auto py-6" x-data="pengaturanPage()" x-init="init()">
        <!-- Header -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Pengaturan</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>

        <!-- Accordion: Kontrol Irigasi -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] mb-6 overflow-hidden">
            <!-- Accordion Header -->
            <div class="flex items-center justify-between py-4 px-6 border-b border-[#E5E5E5]">
                <h2 class="text-lg font-bold text-[#4F4F4F]">Kontrol Irigasi</h2>
                <button type="button" 
                    @click="toggleEdit('kontrolIrigasi')"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-[#4F4F4F] border border-[#C2C2C2] rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
            </div>

            <!-- Accordion Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kelembaban Tanah -->
                    <div class="border border-[#E5E5E5] rounded-xl p-5">
                        <h3 class="text-base font-bold text-[#4F4F4F] mb-2">Kelembaban Tanah</h3>
                        <p class="text-sm text-[#6B7280] mb-6">
                            Tentukan batas nilai kelembaban tanah untuk mengatur nyala atau mati pompa secara otomatis
                        </p>

                        <!-- Dual Range Slider - Fixed Version -->
                        <div class="mb-6">
                            <div class="relative pt-8 pb-2">
                                <!-- Min Value Label -->
                                <div class="absolute top-0 transform -translate-x-1/2 bg-primary text-white text-xs font-semibold px-2 py-1 rounded z-10"
                                    :style="'left: calc(' + kontrolIrigasi.kelembaban.min + '% + ' + (8 - kontrolIrigasi.kelembaban.min * 0.16) + 'px)'">
                                    <span x-text="kontrolIrigasi.kelembaban.min"></span>
                                </div>
                                <!-- Max Value Label -->
                                <div class="absolute top-0 transform -translate-x-1/2 bg-primary text-white text-xs font-semibold px-2 py-1 rounded z-10"
                                    :style="'left: calc(' + kontrolIrigasi.kelembaban.max + '% + ' + (8 - kontrolIrigasi.kelembaban.max * 0.16) + 'px)'">
                                    <span x-text="kontrolIrigasi.kelembaban.max"></span>
                                </div>
                                
                                <!-- Slider Container -->
                                <div class="relative h-2">
                                    <!-- Background Track -->
                                    <div class="absolute w-full h-2 bg-[#E5E5E5] rounded-full"></div>
                                    
                                    <!-- Active Range -->
                                    <div class="absolute h-2 bg-primary rounded-full"
                                        :style="'left: ' + kontrolIrigasi.kelembaban.min + '%; width: ' + (kontrolIrigasi.kelembaban.max - kontrolIrigasi.kelembaban.min) + '%'">
                                    </div>
                                    
                                    <!-- Min Slider (pointer-events based on position) -->
                                    <input type="range" min="0" max="100" step="1"
                                        x-model.number="kontrolIrigasi.kelembaban.min"
                                        :disabled="!isEditing.kontrolIrigasi"
                                        @input="kontrolIrigasi.kelembaban.min = Math.min($event.target.value, kontrolIrigasi.kelembaban.max - 5)"
                                        class="dual-range-slider dual-range-min absolute w-full h-2 appearance-none bg-transparent cursor-pointer disabled:cursor-not-allowed" />
                                    
                                    <!-- Max Slider -->
                                    <input type="range" min="0" max="100" step="1"
                                        x-model.number="kontrolIrigasi.kelembaban.max"
                                        :disabled="!isEditing.kontrolIrigasi"
                                        @input="kontrolIrigasi.kelembaban.max = Math.max($event.target.value, kontrolIrigasi.kelembaban.min + 5)"
                                        class="dual-range-slider dual-range-max absolute w-full h-2 appearance-none bg-transparent cursor-pointer disabled:cursor-not-allowed" />
                                </div>
                                
                                <!-- Tick Marks -->
                                <div class="flex justify-between mt-4 px-1">
                                    <span class="text-xs text-gray-400">0</span>
                                    <span class="text-xs text-gray-400">25</span>
                                    <span class="text-xs text-gray-400">50</span>
                                    <span class="text-xs text-gray-400">75</span>
                                    <span class="text-xs text-gray-400">100</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-[#6B7280] italic mb-6">
                            *Pastikan Anda sudah memeriksa kondisi lahan, agar tidak terjadi kelebihan maupun kekurangan air
                        </p>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button type="button" 
                                @click="saveKontrolIrigasi()"
                                :disabled="!isEditing.kontrolIrigasi"
                                class="flex-1 btn-3d-green disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
                                Simpan
                            </button>
                            <button type="button" 
                                @click="resetKontrolIrigasiKelembaban()"
                                :disabled="!isEditing.kontrolIrigasi"
                                class="flex-1 h-11 bg-white text-[#4F4F4F] font-semibold border border-[#C2C2C2] rounded-xl hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Kondisi Lahan -->
                    <div class="border border-[#E5E5E5] rounded-xl p-5">
                        <h3 class="text-base font-medium text-[#4F4F4F] mb-2">Kondisi Lahan</h3>
                        <p class="text-sm text-[#6B7280] mb-6">
                            Tentukan nilai kelembaban tanah untuk mengatur status kelembaban lahan (kering, lembab, dan basah)
                        </p>

                        <!-- Tabs: Kering, Lembab, Basah - Centered -->
                        <div class="flex justify-center gap-4 mb-6">
                            <template x-for="option in ['Kering', 'Lembab', 'Basah']" :key="option">
                                <button type="button"
                                    @click="if(isEditing.kontrolIrigasi) kontrolIrigasi.kondisiLahan.activeTab = option"
                                    :class="kontrolIrigasi.kondisiLahan.activeTab === option 
                                        ? 'text-text-green font-semibold underline underline-offset-8 decoration-2 decoration-text-green' 
                                        : 'text-gray-500'"
                                    :disabled="!isEditing.kontrolIrigasi"
                                    class="text-sm transition-colors disabled:cursor-not-allowed"
                                    x-text="option">
                                </button>
                            </template>
                        </div>

                        <!-- Input Nilai per Tab -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-[#4F4F4F] mb-2">Masukkan Nilai</label>
                            <div class="relative">
                                <input type="number" 
                                    x-model.number="kontrolIrigasi.kondisiLahan.values[kontrolIrigasi.kondisiLahan.activeTab]"
                                    :disabled="!isEditing.kontrolIrigasi"
                                    min="0" max="100"
                                    class="w-full h-11 px-4 pr-10 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary disabled:bg-gray-50 disabled:cursor-not-allowed" />
                                <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-sm text-gray-400">%</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button type="button" 
                                @click="saveKontrolIrigasi()"
                                :disabled="!isEditing.kontrolIrigasi"
                                class="flex-1 btn-3d-green disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
                                Simpan
                            </button>
                            <button type="button" 
                                @click="resetKontrolIrigasiKondisi()"
                                :disabled="!isEditing.kontrolIrigasi"
                                class="flex-1 h-11 bg-white text-[#4F4F4F] font-semibold border border-[#C2C2C2] rounded-xl hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accordion: Safety Timeout -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] overflow-hidden">
            <!-- Accordion Header -->
            <div class="flex items-center justify-between py-4 px-6 border-b border-[#E5E5E5]">
                <h2 class="text-lg font-bold text-[#4F4F4F]">Safety Timeout</h2>
                <button type="button" 
                    @click="toggleEdit('safetyTimeout')"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-[#4F4F4F] border border-[#C2C2C2] rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
            </div>

            <!-- Accordion Content -->
            <div class="p-6">
                <!-- Pengaman Irigasi - Full Width -->
                <div class="border border-[#E5E5E5] rounded-xl p-5">
                    <h3 class="text-base font-bold text-[#4F4F4F] mb-2">Pengaman Irigasi</h3>
                    <p class="text-sm text-[#6B7280] mb-6">
                        Tentukan lama waktu ketika alat tidak mengirim data digital ke sistem (menit)
                    </p>

                    <!-- Dual Range Slider - Fixed Version -->
                    <div class="mb-6">
                        <div class="relative pt-8 pb-2">
                            <!-- Min Value Label -->
                            <div class="absolute top-0 transform -translate-x-1/2 bg-primary text-white text-xs font-semibold px-2 py-1 rounded z-10"
                                :style="'left: calc(' + ((safetyTimeout.pengaman.min - 1) / 9 * 100) + '% + ' + (8 - ((safetyTimeout.pengaman.min - 1) / 9 * 100) * 0.16) + 'px)'">
                                <span x-text="safetyTimeout.pengaman.min"></span>
                            </div>
                            <!-- Max Value Label -->
                            <div class="absolute top-0 transform -translate-x-1/2 bg-primary text-white text-xs font-semibold px-2 py-1 rounded z-10"
                                :style="'left: calc(' + ((safetyTimeout.pengaman.max - 1) / 9 * 100) + '% + ' + (8 - ((safetyTimeout.pengaman.max - 1) / 9 * 100) * 0.16) + 'px)'">
                                <span x-text="safetyTimeout.pengaman.max"></span>
                            </div>
                            
                            <!-- Slider Container -->
                            <div class="relative h-2">
                                <!-- Background Track -->
                                <div class="absolute w-full h-2 bg-[#E5E5E5] rounded-full"></div>
                                
                                <!-- Active Range -->
                                <div class="absolute h-2 bg-primary rounded-full"
                                    :style="'left: ' + ((safetyTimeout.pengaman.min - 1) / 9 * 100) + '%; width: ' + ((safetyTimeout.pengaman.max - safetyTimeout.pengaman.min) / 9 * 100) + '%'">
                                </div>
                                
                                <!-- Min Slider (pointer-events based on position) -->
                                <input type="range" min="1" max="10" step="1"
                                    x-model.number="safetyTimeout.pengaman.min"
                                    :disabled="!isEditing.safetyTimeout"
                                    @input="safetyTimeout.pengaman.min = Math.min($event.target.value, safetyTimeout.pengaman.max - 1)"
                                    class="dual-range-slider dual-range-min absolute w-full h-2 appearance-none bg-transparent cursor-pointer disabled:cursor-not-allowed" />
                                
                                <!-- Max Slider -->
                                <input type="range" min="1" max="10" step="1"
                                    x-model.number="safetyTimeout.pengaman.max"
                                    :disabled="!isEditing.safetyTimeout"
                                    @input="safetyTimeout.pengaman.max = Math.max($event.target.value, safetyTimeout.pengaman.min + 1)"
                                    class="dual-range-slider dual-range-max absolute w-full h-2 appearance-none bg-transparent cursor-pointer disabled:cursor-not-allowed" />
                            </div>
                            
                            <!-- Tick Marks -->
                            <div class="flex justify-between mt-4 px-1">
                                <span class="text-xs text-gray-400">1 menit</span>
                                <span class="text-xs text-gray-400">2 menit</span>
                                <span class="text-xs text-gray-400">3 menit</span>
                                <span class="text-xs text-gray-400">4 menit</span>
                                <span class="text-xs text-gray-400">5 menit</span>
                                <span class="text-xs text-gray-400">6 menit</span>
                                <span class="text-xs text-gray-400">7 menit</span>
                                <span class="text-xs text-gray-400">8 menit</span>
                                <span class="text-xs text-gray-400">9 menit</span>
                                <span class="text-xs text-gray-400">10 menit</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-[#6B7280] italic mb-6">
                        *fitur ini memastikan supaya irigasi yang sedang aktif dapat dimatikan otomatis ketika alat tidak mengirim data digital ke sistem. Sehingga irigasi dapat terpantau dengan maksimal
                    </p>

                    <!-- Buttons -->
                    <div class="flex gap-3">
                        <button type="button" 
                            @click="saveSafetyTimeout()"
                            :disabled="!isEditing.safetyTimeout"
                            class="flex-1 btn-3d-green disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
                            Simpan
                        </button>
                        <button type="button" 
                            @click="resetSafetyTimeoutPengaman()"
                            :disabled="!isEditing.safetyTimeout"
                            class="flex-1 h-11 bg-white text-[#4F4F4F] font-semibold border border-[#C2C2C2] rounded-xl hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Dual Range Slider Styles */
    .dual-range-slider {
        -webkit-appearance: none;
        appearance: none;
        height: 8px;
        background: transparent;
        pointer-events: none; /* Disable pointer events on track */
    }
    
    .dual-range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 3px solid #67B744;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        margin-top: -6px;
        pointer-events: auto; /* Enable pointer events only on thumb */
        position: relative;
        z-index: 10;
    }
    
    .dual-range-slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 3px solid #67B744;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        pointer-events: auto; /* Enable pointer events only on thumb */
    }
    
    .dual-range-slider::-webkit-slider-runnable-track {
        height: 8px;
        background: transparent;
    }
    
    .dual-range-slider::-moz-range-track {
        height: 8px;
        background: transparent;
    }
    
    /* Min slider styling */
    .dual-range-min {
        z-index: 5;
    }
    
    /* Max slider styling - higher z-index but thumb should not block min */
    .dual-range-max {
        z-index: 4;
    }
    
    .dual-range-slider:disabled::-webkit-slider-thumb {
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .dual-range-slider:disabled::-moz-range-thumb {
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    function pengaturanPage() {
        return {
            // Current date display
            currentDate: '',
            
            // Edit mode states
            isEditing: {
                kontrolIrigasi: false,
                safetyTimeout: false
            },
            
            // Kontrol Irigasi settings
            kontrolIrigasi: {
                kelembaban: {
                    min: {{ $settings['kontrol_irigasi']['kelembaban_tanah']['min'] ?? 20 }},
                    max: {{ $settings['kontrol_irigasi']['kelembaban_tanah']['max'] ?? 65 }}
                },
                kondisiLahan: {
                    activeTab: 'Kering',
                    values: {
                        Kering: {{ $settings['kontrol_irigasi']['kondisi_lahan']['kering'] ?? 20 }},
                        Lembab: {{ $settings['kontrol_irigasi']['kondisi_lahan']['lembab'] ?? 50 }},
                        Basah: {{ $settings['kontrol_irigasi']['kondisi_lahan']['basah'] ?? 80 }}
                    }
                }
            },
            
            // Safety Timeout settings
            safetyTimeout: {
                pengaman: {
                    min: {{ $settings['safety_timeout']['pengaman_irigasi']['min'] ?? 1 }},
                    max: {{ $settings['safety_timeout']['pengaman_irigasi']['max'] ?? 3 }}
                }
            },
            
            // Default values for reset
            defaults: {
                kelembaban: { min: 20, max: 65 },
                kondisiLahan: { Kering: 20, Lembab: 50, Basah: 80 },
                pengamanIrigasi: { min: 1, max: 3 }
            },
            
            // Initialize component
            init() {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                this.currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            },
            
            // Toggle edit mode
            toggleEdit(section) {
                this.isEditing[section] = !this.isEditing[section];
            },
            
            // Save Kontrol Irigasi
            saveKontrolIrigasi() {
                // Data to send to backend
                const payload = {
                    kelembaban_tanah: {
                        min: this.kontrolIrigasi.kelembaban.min,
                        max: this.kontrolIrigasi.kelembaban.max
                    },
                    kondisi_lahan: {
                        kering: this.kontrolIrigasi.kondisiLahan.values.Kering,
                        lembab: this.kontrolIrigasi.kondisiLahan.values.Lembab,
                        basah: this.kontrolIrigasi.kondisiLahan.values.Basah
                    }
                };
                
                console.log('Saving Kontrol Irigasi:', payload);
                this.isEditing.kontrolIrigasi = false;
                
                // Show success notification
                if (typeof window.dispatchEvent === 'function') {
                    window.dispatchEvent(new CustomEvent('show-notification', {
                        detail: { type: 'success', message: 'Pengaturan Kontrol Irigasi berhasil disimpan!' }
                    }));
                }
            },
            
            // Save Safety Timeout
            saveSafetyTimeout() {
                // Data to send to backend
                const payload = {
                    pengaman_irigasi: {
                        min: this.safetyTimeout.pengaman.min,
                        max: this.safetyTimeout.pengaman.max
                    }
                };
                
                console.log('Saving Safety Timeout:', payload);
                this.isEditing.safetyTimeout = false;
                
                // Show success notification
                if (typeof window.dispatchEvent === 'function') {
                    window.dispatchEvent(new CustomEvent('show-notification', {
                        detail: { type: 'success', message: 'Pengaturan Safety Timeout berhasil disimpan!' }
                    }));
                }
            },
            
            // Reset functions for Kontrol Irigasi
            resetKontrolIrigasiKelembaban() {
                this.kontrolIrigasi.kelembaban = { ...this.defaults.kelembaban };
            },
            
            resetKontrolIrigasiKondisi() {
                this.kontrolIrigasi.kondisiLahan.values = { ...this.defaults.kondisiLahan };
            },
            
            // Reset functions for Safety Timeout
            resetSafetyTimeoutPengaman() {
                this.safetyTimeout.pengaman = { ...this.defaults.pengamanIrigasi };
            }
        }
    }
</script>
@endpush
