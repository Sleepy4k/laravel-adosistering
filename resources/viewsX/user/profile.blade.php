@extends('layouts.user')

@section('content')
    <div class="w-full max-w-7xl mx-auto py-6" x-data="{
        editInformasiPribadi: false,
        editLokasi: false,
        showUbahPassword: false,
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
        originalProfile: null,
        init() {
            this.originalProfile = JSON.parse(JSON.stringify(this.profile));
        },
        resetInformasiPribadi() {
            this.profile.nama_lengkap = this.originalProfile.nama_lengkap;
            this.profile.nomor_whatsapp = this.originalProfile.nomor_whatsapp;
            this.profile.email = this.originalProfile.email;
            this.profile.bio = this.originalProfile.bio;
            this.editInformasiPribadi = false;
        },
        resetLokasi() {
            this.profile.negara = this.originalProfile.negara;
            this.profile.provinsi = this.originalProfile.provinsi;
            this.profile.kota = this.originalProfile.kota;
            this.profile.kode_pos = this.originalProfile.kode_pos;
            this.editLokasi = false;
        },
        simpanInformasiPribadi() {
            this.originalProfile.nama_lengkap = this.profile.nama_lengkap;
            this.originalProfile.nomor_whatsapp = this.profile.nomor_whatsapp;
            this.originalProfile.email = this.profile.email;
            this.originalProfile.bio = this.profile.bio;
            this.editInformasiPribadi = false;
        },
        simpanLokasi() {
            this.originalProfile.negara = this.profile.negara;
            this.originalProfile.provinsi = this.profile.provinsi;
            this.originalProfile.kota = this.profile.kota;
            this.originalProfile.kode_pos = this.profile.kode_pos;
            this.editLokasi = false;
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
                    x-show="!editInformasiPribadi"
                    @click="editInformasiPribadi = true"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <img src="/assets/icons/edit.svg" alt="Edit" class="w-4 h-4 brightness-0" />
                    <span>Edit</span>
                </button>
                <div x-show="editInformasiPribadi" class="flex items-center gap-2">
                    <button 
                        @click="resetInformasiPribadi()"
                        class="px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button 
                        @click="simpanInformasiPribadi()"
                        class="px-4 py-2 bg-primary text-white rounded-xl text-sm hover:bg-primary-dark transition-colors"
                    >
                        Simpan
                    </button>
                </div>
            </div>
            <div class="-mx-6 h-px bg-[#D4D4D4] mb-6"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Nama Lengkap</label>
                    <template x-if="!editInformasiPribadi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.nama_lengkap"></span>
                    </template>
                    <template x-if="editInformasiPribadi">
                        <input 
                            type="text" 
                            x-model="profile.nama_lengkap"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
                </div>
                <!-- Nomor WhatsApp -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Nomor WhatsApp</label>
                    <template x-if="!editInformasiPribadi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.nomor_whatsapp"></span>
                    </template>
                    <template x-if="editInformasiPribadi">
                        <input 
                            type="text" 
                            x-model="profile.nomor_whatsapp"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
                </div>
                <!-- Email -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Email</label>
                    <template x-if="!editInformasiPribadi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.email"></span>
                    </template>
                    <template x-if="editInformasiPribadi">
                        <input 
                            type="email" 
                            x-model="profile.email"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
                </div>
                <!-- Bio -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Bio</label>
                    <template x-if="!editInformasiPribadi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.bio"></span>
                    </template>
                    <template x-if="editInformasiPribadi">
                        <input 
                            type="text" 
                            x-model="profile.bio"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
                </div>
            </div>
        </div>

        <!-- Lokasi Card -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Lokasi</h2>
                <button 
                    x-show="!editLokasi"
                    @click="editLokasi = true"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <img src="/assets/icons/edit.svg" alt="Edit" class="w-4 h-4 brightness-0" />
                    <span>Edit</span>
                </button>
                <div x-show="editLokasi" class="flex items-center gap-2">
                    <button 
                        @click="resetLokasi()"
                        class="px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button 
                        @click="simpanLokasi()"
                        class="px-4 py-2 bg-primary text-white rounded-xl text-sm hover:bg-primary-dark transition-colors"
                    >
                        Simpan
                    </button>
                </div>
            </div>
            <div class="-mx-6 h-px bg-[#D4D4D4] mb-6"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Negara -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Negara</label>
                    <template x-if="!editLokasi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.negara"></span>
                    </template>
                    <template x-if="editLokasi">
                        <input 
                            type="text" 
                            x-model="profile.negara"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
                </div>
                <!-- Provinsi -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Provinsi</label>
                    <template x-if="!editLokasi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.provinsi"></span>
                    </template>
                    <template x-if="editLokasi">
                        <input 
                            type="text" 
                            x-model="profile.provinsi"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
                </div>
                <!-- Kota / Kabupaten -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kota / Kabupaten</label>
                    <template x-if="!editLokasi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.kota"></span>
                    </template>
                    <template x-if="editLokasi">
                        <input 
                            type="text" 
                            x-model="profile.kota"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
                </div>
                <!-- Kode Pos -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kode Pos</label>
                    <template x-if="!editLokasi">
                        <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.kode_pos"></span>
                    </template>
                    <template x-if="editLokasi">
                        <input 
                            type="text" 
                            x-model="profile.kode_pos"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </template>
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
                class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6"
                x-data="{
                    passwordLama: '',
                    passwordBaru: '',
                    konfirmasiPassword: '',
                    showPasswordLama: false,
                    showPasswordBaru: false,
                    showKonfirmasiPassword: false
                }"
            >
                <h3 class="text-lg font-semibold text-[#4F4F4F] mb-6">Ubah Kata Sandi</h3>
                
                <!-- Password Lama -->
                <div class="flex flex-col gap-2 mb-4">
                    <label class="text-sm font-medium text-[#4F4F4F]">Kata Sandi Lama</label>
                    <div class="relative">
                        <input 
                            :type="showPasswordLama ? 'text' : 'password'" 
                            x-model="passwordLama"
                            placeholder="Masukkan kata sandi lama"
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
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
                <div class="flex flex-col gap-2 mb-4">
                    <label class="text-sm font-medium text-[#4F4F4F]">Kata Sandi Baru</label>
                    <div class="relative">
                        <input 
                            :type="showPasswordBaru ? 'text' : 'password'" 
                            x-model="passwordBaru"
                            placeholder="Masukkan kata sandi baru"
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
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
                <div class="flex flex-col gap-2 mb-6">
                    <label class="text-sm font-medium text-[#4F4F4F]">Konfirmasi Kata Sandi Baru</label>
                    <div class="relative">
                        <input 
                            :type="showKonfirmasiPassword ? 'text' : 'password'" 
                            x-model="konfirmasiPassword"
                            placeholder="Konfirmasi kata sandi baru"
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
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

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <button 
                        @click="showUbahPassword = false; passwordLama = ''; passwordBaru = ''; konfirmasiPassword = '';"
                        class="px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                    <button 
                        @click="showUbahPassword = false; passwordLama = ''; passwordBaru = ''; konfirmasiPassword = '';"
                        class="px-4 py-2 bg-primary text-white rounded-xl text-sm hover:bg-primary-dark transition-colors"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
