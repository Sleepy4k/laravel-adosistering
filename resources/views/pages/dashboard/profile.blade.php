@extends('layouts.user')

@section('title', 'Profil')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{
        editInformasiPribadi: false,
        editLokasi: false,
        showUbahPassword: false,
        profile: {
            nama_lengkap: '{{ $user->name ?? 'User' }}',
            nomor_whatsapp: '{{ $user->phone ?? '-' }}',
            email: '{{ $user->email ?? '-' }}',
            bio: '{{ $user->bio ?? '-' }}',
            negara: '{{ $user->country ?? 'Indonesia' }}',
            provinsi: '{{ $user->province ?? '-' }}',
            kota: '{{ $user->city ?? '-' }}',
            kode_pos: '{{ $user->postal_code ?? '-' }}'
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
            // TODO: Submit to backend
        },
        simpanLokasi() {
            this.originalProfile.negara = this.profile.negara;
            this.originalProfile.provinsi = this.profile.provinsi;
            this.originalProfile.kota = this.profile.kota;
            this.originalProfile.kode_pos = this.profile.kode_pos;
            this.editLokasi = false;
            // TODO: Submit to backend
        }
    }">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Profil</h1>
                <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
            </div>
        </div>

        <!-- Profil Saya Card -->
        @can('profile.view')
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <h2 class="text-lg font-semibold text-[#4F4F4F] mb-4">Profil Saya</h2>
            <div class="flex items-center gap-4">
                <img src="{{ asset('assets/images/default-avatar.jpg') }}" alt="Profile" class="w-16 h-16 rounded-full object-cover border border-gray-200 shadow-sm" />
                <div class="flex flex-col">
                    <span class="text-lg font-semibold text-[#4F8936]" x-text="profile.nama_lengkap"></span>
                    <span class="text-sm text-gray-500" x-text="profile.bio"></span>
                    <span class="text-sm text-gray-400" x-text="profile.nomor_whatsapp"></span>
                </div>
            </div>
        </div>
        @endcan

        <!-- Informasi Pribadi Card -->
        @can('profile.view')
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Informasi Pribadi</h2>
                @can('profile.edit.basic')
                <button 
                    x-show="!editInformasiPribadi"
                    @click="editInformasiPribadi = true"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
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
                        class="px-4 py-2 bg-[#67B744] text-white rounded-xl text-sm hover:bg-[#5aa33d] transition-colors"
                    >
                        Simpan
                    </button>
                </div>
                @endcan
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
                        />
                    </template>
                </div>
            </div>
        </div>
        @endcan

        <!-- Lokasi Card -->
        @can('profile.view')
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Lokasi</h2>
                @can('profile.edit.other')
                <button 
                    x-show="!editLokasi"
                    @click="editLokasi = true"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
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
                        class="px-4 py-2 bg-[#67B744] text-white rounded-xl text-sm hover:bg-[#5aa33d] transition-colors"
                    >
                        Simpan
                    </button>
                </div>
                @endcan
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
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
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
                        />
                    </template>
                </div>
            </div>
        </div>
        @endcan

        <!-- Keamanan Card -->
        @can('profile.edit.credential')
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
        @endcan

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
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
                        />
                        <button 
                            type="button"
                            @click="showPasswordLama = !showPasswordLama"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="!showPasswordLama" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            <svg x-show="showPasswordLama" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
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
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
                        />
                        <button 
                            type="button"
                            @click="showPasswordBaru = !showPasswordBaru"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="!showPasswordBaru" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            <svg x-show="showPasswordBaru" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
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
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-[#67B744] focus:border-[#67B744]"
                        />
                        <button 
                            type="button"
                            @click="showKonfirmasiPassword = !showKonfirmasiPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="!showKonfirmasiPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            <svg x-show="showKonfirmasiPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
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
                        class="px-4 py-2 bg-[#67B744] text-white rounded-xl text-sm hover:bg-[#5aa33d] transition-colors"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
