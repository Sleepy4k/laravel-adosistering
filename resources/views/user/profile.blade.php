@extends('layouts.user')

@section('content')
    <div class="w-full max-w-7xl mx-auto py-6" x-data="{
        showEditInformasiPribadi: false,
        showEditLokasi: false,
        showUbahPassword: false,
        showOtpModal: false,
        showConfirmPassword: false,
        showSuccessInformasi: false,
        showSuccessLokasi: false,
        showSuccessPassword: false,
        profile: {
            nama_lengkap: '{{ $profile['nama_lengkap'] ?? 'Ahmad Subardjo' }}',
            nomor_whatsapp: '{{ $profile['nomor_whatsapp'] ?? '081234567890' }}',
            email: '{{ $profile['email'] ?? 'ahmadsubardjo@gmail.com' }}',
            bio: '{{ $profile['bio'] ?? 'Pengelola Kawista' }}',
            negara: '{{ $profile['negara'] ?? 'Indonesia' }}',
            provinsi: '{{ $profile['provinsi'] ?? 'Jawa Tengah' }}',
            kota: '{{ $profile['kota'] ?? 'Cilacap' }}',
            kode_pos: '{{ $profile['kode_pos'] ?? '40152' }}'
        },
        tempProfile: {},
        originalProfile: null,
        init() {
            this.originalProfile = JSON.parse(JSON.stringify(this.profile));
        },
        openEditInformasiPribadi() {
            this.tempProfile = JSON.parse(JSON.stringify(this.profile));
            this.showEditInformasiPribadi = true;
        },
        openEditLokasi() {
            this.tempProfile = JSON.parse(JSON.stringify(this.profile));
            this.showEditLokasi = true;
        },
        simpanInformasiPribadi() {
            this.profile.nama_lengkap = this.tempProfile.nama_lengkap;
            this.profile.nomor_whatsapp = this.tempProfile.nomor_whatsapp;
            this.profile.email = this.tempProfile.email;
            this.profile.bio = this.tempProfile.bio;
            this.originalProfile.nama_lengkap = this.tempProfile.nama_lengkap;
            this.originalProfile.nomor_whatsapp = this.tempProfile.nomor_whatsapp;
            this.originalProfile.email = this.tempProfile.email;
            this.originalProfile.bio = this.tempProfile.bio;
            this.showEditInformasiPribadi = false;
            this.showSuccessInformasi = true;
        },
        simpanLokasi() {
            this.profile.negara = this.tempProfile.negara;
            this.profile.provinsi = this.tempProfile.provinsi;
            this.profile.kota = this.tempProfile.kota;
            this.profile.kode_pos = this.tempProfile.kode_pos;
            this.originalProfile.negara = this.tempProfile.negara;
            this.originalProfile.provinsi = this.tempProfile.provinsi;
            this.originalProfile.kota = this.tempProfile.kota;
            this.originalProfile.kode_pos = this.tempProfile.kode_pos;
            this.showEditLokasi = false;
            this.showSuccessLokasi = true;
        },
        konfirmasiUbahPassword() {
            this.showUbahPassword = false;
            this.showConfirmPassword = true;
        },
        prosesUbahPassword() {
            this.showConfirmPassword = false;
            this.showSuccessPassword = true;
        }
    }">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Profil</h1>
                <div class="flex items-center gap-3">
                    <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const now = new Date();
                    currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
                    <img src="/assets/images/default-avatar.jpg" alt="Profile" class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm" />
                </div>
            </div>
        </div>

        <!-- Profil Saya Card -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <h2 class="text-lg font-semibold text-[#4F4F4F] mb-4">Profil Saya</h2>
            <div class="flex items-center gap-4">
                <img src="/assets/images/default-avatar.jpg" alt="Profile" class="w-16 h-16 rounded-full object-cover border border-gray-200 shadow-sm" />
                <div class="flex flex-col">
                    <span class="text-lg font-semibold text-text-green" x-text="profile.nama_lengkap"></span>
                    <span class="text-sm text-gray-500" x-text="profile.bio"></span>
                    <span class="text-sm text-gray-400" x-text="profile.nomor_whatsapp"></span>
                </div>
            </div>
        </div>

        <!-- Informasi Pribadi Card -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Informasi Pribadi</h2>
                <button 
                    @click="openEditInformasiPribadi()"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <img src="/assets/icons/edit.svg" alt="Edit" class="w-4 h-4 brightness-0" />
                    <span>Edit</span>
                </button>
            </div>
            <div class="-mx-6 h-px bg-[#D4D4D4] mb-6"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Nama Lengkap</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.nama_lengkap"></span>
                </div>
                <!-- Nomor WhatsApp -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Nomor WhatsApp</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.nomor_whatsapp"></span>
                </div>
                <!-- Email -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Email</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.email"></span>
                </div>
                <!-- Bio -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Bio</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.bio"></span>
                </div>
            </div>
        </div>

        <!-- Lokasi Card -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Lokasi</h2>
                <button 
                    @click="openEditLokasi()"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <img src="/assets/icons/edit.svg" alt="Edit" class="w-4 h-4 brightness-0" />
                    <span>Edit</span>
                </button>
            </div>
            <div class="-mx-6 h-px bg-[#D4D4D4] mb-6"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Negara -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Negara</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.negara"></span>
                </div>
                <!-- Provinsi -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Provinsi</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.provinsi"></span>
                </div>
                <!-- Kota / Kabupaten -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kota / Kabupaten</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.kota"></span>
                </div>
                <!-- Kode Pos -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kode Pos</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.kode_pos"></span>
                </div>
            </div>
        </div>

        <!-- Keamanan Card -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Keamanan</h2>
                <button 
                    @click="showUbahPassword = true"
                    class="px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    Ubah Kata Sandi
                </button>
            </div>
            <div class="-mx-6 h-px bg-[#D4D4D4] mb-6"></div>

            <!-- Security Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Username -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm text-gray-500">Username</label>
                    <p class="text-base text-[#4F4F4F] font-medium">{{ $profile['username'] ?? 'maoscilacap' }}</p>
                </div>

                <!-- Kata Sandi -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm text-gray-500">Kata Sandi</label>
                    <p class="text-base text-[#4F4F4F] font-medium">{{ $profile['password_masked'] ?? '**********' }}</p>
                </div>
            </div>
        </div>

        <!-- Modal Edit Informasi Pribadi -->
        <div 
            x-show="showEditInformasiPribadi" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="showEditInformasiPribadi = false"
            style="display: none;"
        >
            <div 
                x-show="showEditInformasiPribadi"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-8 relative"
            >
                <!-- Close Button -->
                <button 
                    @click="showEditInformasiPribadi = false"
                    class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <h3 class="text-xl font-bold text-[#4F4F4F] mb-6">Informasi Pribadi</h3>
                
                <div class="space-y-4 mb-6">
                    <!-- Nama Lengkap -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Nama Lengkap</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.nama_lengkap"
                            placeholder="Ahmad Subarjo"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Nomor WhatsApp</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.nomor_whatsapp"
                            placeholder="081234567890"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Email</label>
                        <input 
                            type="email" 
                            x-model="tempProfile.email"
                            placeholder="ahmadsubarjo@gmail.com"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Bio -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Bio</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.bio"
                            placeholder="Pengelola Kawista"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        @click="simpanInformasiPribadi()"
                        class="btn-3d-green w-full h-12 text-sm"
                    >
                        Berikutnya
                    </button>
                    <button 
                        @click="showEditInformasiPribadi = false"
                        class="w-full h-12 bg-white border border-[#C2C2C2] text-[#4F4F4F] rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Lokasi -->
        <div 
            x-show="showEditLokasi" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="showEditLokasi = false"
            style="display: none;"
        >
            <div 
                x-show="showEditLokasi"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-8 relative"
            >
                <!-- Close Button -->
                <button 
                    @click="showEditLokasi = false"
                    class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <h3 class="text-xl font-bold text-[#4F4F4F] mb-6">Lokasi</h3>
                
                <div class="space-y-4 mb-6">
                    <!-- Negara -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Negara</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.negara"
                            placeholder="Indonesia"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Provinsi -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Provinsi</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.provinsi"
                            placeholder="Jawa Tengah"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Kota / Kabupaten -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Kota / Kabupaten</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.kota"
                            placeholder="Cilacap"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Kode Pos -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Kode Pos</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.kode_pos"
                            placeholder="40152"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        @click="simpanLokasi()"
                        class="btn-3d-green w-full h-12 text-sm"
                    >
                        Berikutnya
                    </button>
                    <button 
                        @click="showEditLokasi = false"
                        class="w-full h-12 bg-white border border-[#C2C2C2] text-[#4F4F4F] rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Ubah Kata Sandi -->
        <div 
            x-show="showUbahPassword" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="showUbahPassword = false"
            style="display: none;"
        >
            <div 
                x-show="showUbahPassword"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-8 relative"
                x-data="{
                    passwordLama: '',
                    passwordBaru: '',
                    konfirmasiPassword: '',
                    showPasswordLama: false,
                    showPasswordBaru: false,
                    showKonfirmasiPassword: false
                }"
            >
                <!-- Close Button -->
                <button 
                    @click="showUbahPassword = false"
                    class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <h3 class="text-xl font-bold text-[#4F4F4F] mb-6">Ubah Kata Sandi</h3>
                
                <div class="space-y-4 mb-6">
                    <!-- Password Lama -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Kata Sandi Lama</label>
                        <div class="relative">
                            <input 
                                :type="showPasswordLama ? 'text' : 'password'" 
                                x-model="passwordLama"
                                placeholder="Masukkan kata sandi lama"
                                class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            />
                            <button 
                                type="button"
                                @click="showPasswordLama = !showPasswordLama"
                                class="absolute right-4 top-1/2 -translate-y-1/2"
                            >
                                <img :src="showPasswordLama ? '/assets/icons/eye_on.svg' : '/assets/icons/eye_off.svg'" alt="Toggle" class="w-5 h-5 brightness-0 opacity-50" />
                            </button>
                        </div>
                    </div>

                    <!-- Password Baru -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Kata Sandi Baru</label>
                        <div class="relative">
                            <input 
                                :type="showPasswordBaru ? 'text' : 'password'" 
                                x-model="passwordBaru"
                                placeholder="Masukkan kata sandi baru"
                                class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            />
                            <button 
                                type="button"
                                @click="showPasswordBaru = !showPasswordBaru"
                                class="absolute right-4 top-1/2 -translate-y-1/2"
                            >
                                <img :src="showPasswordBaru ? '/assets/icons/eye_on.svg' : '/assets/icons/eye_off.svg'" alt="Toggle" class="w-5 h-5 brightness-0 opacity-50" />
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm text-[#4F4F4F]">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input 
                                :type="showKonfirmasiPassword ? 'text' : 'password'" 
                                x-model="konfirmasiPassword"
                                placeholder="Konfirmasi kata sandi baru"
                                class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            />
                            <button 
                                type="button"
                                @click="showKonfirmasiPassword = !showKonfirmasiPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2"
                            >
                                <img :src="showKonfirmasiPassword ? '/assets/icons/eye_on.svg' : '/assets/icons/eye_off.svg'" alt="Toggle" class="w-5 h-5 brightness-0 opacity-50" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        @click="showUbahPassword = false; $nextTick(() => { showOtpModal = true })"
                        class="btn-3d-green w-full h-12 text-sm"
                    >
                        Konfirmasi
                    </button>
                    <button 
                        @click="showUbahPassword = false; passwordLama = ''; passwordBaru = ''; konfirmasiPassword = '';"
                        class="w-full h-12 bg-white border border-[#C2C2C2] text-[#4F4F4F] rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal OTP Verification -->
        <x-global.otp-modal 
            show="showOtpModal"
            email="{{ $profile['email'] ?? 'ahmadsubardjo@gmail.com' }}"
            onVerify="showOtpModal = false; $nextTick(() => { showConfirmPassword = true })"
            onResend="console.log('OTP resent')"
        />

        <!-- Confirmation Modal: Ubah Password -->
        <x-global.confirmation-modal 
            x-show="showConfirmPassword"
            x-cloak
            title="Anda Sudah Yakin ?"
            message="Kata sandi akan dirubah dan akan digunakan ketika anda ingin masuk ke sistem"
            confirmText="Saya Yakin"
            cancelText="Batal"
            type="warning"
            x-on:confirm="prosesUbahPassword()"
            x-on:cancel="showConfirmPassword = false"
        />

        <!-- Success Notification: Informasi Pribadi -->
        <div 
            x-show="showSuccessInformasi" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display: none;"
        >
            <div 
                x-show="showSuccessInformasi"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-8 relative text-center"
            >
                <!-- Close Button -->
                <button 
                    @click="showSuccessInformasi = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-[#4F4F4F] mb-2">Berhasil Mengubah Data</h3>
                
                <!-- Message -->
                <p class="text-sm text-gray-500 mb-6">Informasi pribadi berhasil diubah dan disimpan.</p>

                <!-- Button -->
                <button 
                    @click="showSuccessInformasi = false"
                    class="w-full h-12 bg-primary text-white rounded-xl text-base font-medium hover:bg-primary-dark transition-colors"
                >
                    Tutup
                </button>
            </div>
        </div>

        <!-- Success Notification: Lokasi -->
        <div 
            x-show="showSuccessLokasi" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display: none;"
        >
            <div 
                x-show="showSuccessLokasi"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-8 relative text-center"
            >
                <!-- Close Button -->
                <button 
                    @click="showSuccessLokasi = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-[#4F4F4F] mb-2">Berhasil Mengubah Data</h3>
                
                <!-- Message -->
                <p class="text-sm text-gray-500 mb-6">Lokasi berhasil diubah dan disimpan.</p>

                <!-- Button -->
                <button 
                    @click="showSuccessLokasi = false"
                    class="w-full h-12 bg-primary text-white rounded-xl text-base font-medium hover:bg-primary-dark transition-colors"
                >
                    Tutup
                </button>
            </div>
        </div>

        <!-- Success Notification: Password -->
        <div 
            x-show="showSuccessPassword" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            style="display: none;"
        >
            <div 
                x-show="showSuccessPassword"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-8 relative text-center"
            >
                <!-- Close Button -->
                <button 
                    @click="showSuccessPassword = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="text-xl font-bold text-[#4F4F4F] mb-2">Berhasil Mengubah Kata Sandi</h3>
                
                <!-- Message -->
                <p class="text-sm text-gray-500 mb-6">Kata sandi berhasil diubah. Anda diharuskan menggunakan kata sandi terbaru ketika ingin masuk ke sistem</p>

                <!-- Button -->
                <button 
                    @click="showSuccessPassword = false"
                    class="btn-3d-green w-full h-12 text-sm"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection
