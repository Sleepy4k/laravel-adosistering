@extends('layouts.dashboard')

@section('title', 'Profil')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{
        showModalInformasiPribadi: false,
        showModalDomisili: false,
        showModalUbahPassword: false,
        profile: {
            nama_lengkap: '{{ $user->name ?? 'Ahmad Subarjo' }}',
            nomor_whatsapp: '{{ $user->phone ?? '081234567890' }}',
            email: '{{ $user->email ?? 'ahmadsubarjo@gmail.com' }}',
            gender: '{{ $user->gender ?? 'Laki-laki' }}',
            alamat: '{{ $user->details->address ?? 'Jl. Merdeka No. 123, Cilacap' }}',
            negara: '{{ $user->country ?? 'Indonesia' }}',
            provinsi: '{{ $user->province ?? 'Jawa Tengah' }}',
            kota: '{{ $user->city ?? 'Cilacap' }}',
            kode_pos: '{{ $user->postal_code ?? '40152' }}',
            username: '{{ $user->username ?? 'maoscilacap' }}'
        },
        tempProfile: null,
        openModalInformasiPribadi() {
            this.tempProfile = JSON.parse(JSON.stringify(this.profile));
            this.showModalInformasiPribadi = true;
        },
        openModalDomisili() {
            this.tempProfile = JSON.parse(JSON.stringify(this.profile));
            this.showModalDomisili = true;
        },
        batalInformasiPribadi() {
            this.tempProfile = null;
            this.showModalInformasiPribadi = false;
        },
        batalDomisili() {
            this.tempProfile = null;
            this.showModalDomisili = false;
        },
        simpanInformasiPribadi() {
            this.profile.nama_lengkap = this.tempProfile.nama_lengkap;
            this.profile.nomor_whatsapp = this.tempProfile.nomor_whatsapp;
            this.profile.email = this.tempProfile.email;
            this.profile.gender = this.tempProfile.gender;
            this.profile.alamat = this.tempProfile.alamat;
            this.showModalInformasiPribadi = false;
            this.tempProfile = null;
            // TODO: Submit to backend
        },
        simpanDomisili() {
            this.profile.negara = this.tempProfile.negara;
            this.profile.provinsi = this.tempProfile.provinsi;
            this.profile.kota = this.tempProfile.kota;
            this.profile.kode_pos = this.tempProfile.kode_pos;
            this.showModalDomisili = false;
            this.tempProfile = null;
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
                <img src="{{ asset('assets/images/default-avatar.jpg') }}" alt="Profile" loading="lazy" class="w-16 h-16 rounded-full object-cover border border-gray-200 shadow-sm" />
                <div class="flex flex-col">
                    <span class="text-lg font-semibold text-text-green" x-text="profile.nama_lengkap"></span>
                    <span class="text-sm text-gray-500" x-text="profile.alamat"></span>
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
                    @click="openModalInformasiPribadi()"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </button>
                @endcan
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
                <!-- Gender -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Jenis Kelamin</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.gender"></span>
                </div>
                <!-- Alamat -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Alamat</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.alamat"></span>
                </div>
            </div>
        </div>
        @endcan

        <!-- Domisili Card -->
        @can('profile.view')
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Domisili</h2>
                @can('profile.edit.other')
                <button
                    @click="openModalDomisili()"
                    class="flex items-center gap-2 px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </button>
                @endcan
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
        @endcan

        <!-- Keamanan Card -->
        @can('profile.edit.credential')
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-[#4F4F4F]">Keamanan</h2>
                <button
                    @click="showModalUbahPassword = true"
                    class="px-4 py-2 border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                >
                    Ubah Kata Sandi
                </button>
            </div>
            <div class="-mx-6 h-px bg-[#D4D4D4] mb-6"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Username</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.username"></span>
                </div>
                <!-- Kata Sandi -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kata Sandi</label>
                    <span class="text-base font-medium text-[#4F4F4F]">**********</span>
                </div>
            </div>
        </div>
        @endcan

        <!-- Modal Informasi Pribadi -->
        <div
            x-show="showModalInformasiPribadi"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="batalInformasiPribadi()"
            style="display: none;"
        >
            <div
                x-show="showModalInformasiPribadi"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-[#4F4F4F]">Informasi Pribadi</h3>
                    <button @click="batalInformasiPribadi()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Nama Lengkap -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Nama Lengkap</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.nama_lengkap"
                            placeholder="Masukkan nama lengkap"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Nomor WhatsApp</label>
                        <input 
                            type="tel" 
                            x-model="tempProfile.nomor_whatsapp"
                            placeholder="Masukkan nomor WhatsApp"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Email</label>
                        <input 
                            type="email" 
                            x-model="tempProfile.email"
                            placeholder="Masukkan email"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>

                    <!-- Gender -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Jenis Kelamin</label>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" :class="open ? 'border-primary' : ''">
                                <span x-text="tempProfile.gender === '' ? 'Pilih jenis kelamin' : tempProfile.gender" :class="tempProfile.gender === '' ? 'text-gray-400' : 'text-[#4F4F4F]'"></span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg" style="display: none;">
                                <button @click="tempProfile.gender = ''; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 first:rounded-t-xl">Pilih jenis kelamin</button>
                                <button @click="tempProfile.gender = 'Laki-laki'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Laki-laki</button>
                                <button @click="tempProfile.gender = 'Perempuan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl">Perempuan</button>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Alamat</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.alamat"
                            placeholder="Masukkan alamat"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-6">
                    <button
                        @click="simpanInformasiPribadi()"
                        class="flex-1 btn-3d-green text-center"
                    >
                        Berikutnya
                    </button>
                    <button
                        @click="batalInformasiPribadi()"
                        class="flex-1 px-6 py-3 border border-[#C2C2C2] rounded-xl text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Domisili -->
        <div
            x-show="showModalDomisili"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="batalDomisili()"
            style="display: none;"
        >
            <div
                x-show="showModalDomisili"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-[#4F4F4F]">Lokasi</h3>
                    <button @click="batalDomisili()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Negara -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Negara</label>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" :class="open ? 'border-primary' : ''">
                                <span x-text="tempProfile.negara === '' ? 'Pilih negara' : tempProfile.negara" :class="tempProfile.negara === '' ? 'text-gray-400' : 'text-[#4F4F4F]'"></span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                <button @click="tempProfile.negara = ''; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 first:rounded-t-xl">Pilih negara</button>
                                <button @click="tempProfile.negara = 'Indonesia'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Indonesia</button>
                                <button @click="tempProfile.negara = 'Malaysia'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Malaysia</button>
                                <button @click="tempProfile.negara = 'Singapura'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Singapura</button>
                                <button @click="tempProfile.negara = 'Thailand'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Thailand</button>
                                <button @click="tempProfile.negara = 'Vietnam'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Vietnam</button>
                                <button @click="tempProfile.negara = 'Filipina'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Filipina</button>
                                <button @click="tempProfile.negara = 'Brunei'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl">Brunei</button>
                            </div>
                        </div>
                    </div>

                    <!-- Provinsi -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Provinsi</label>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" :class="open ? 'border-primary' : ''">
                                <span x-text="tempProfile.provinsi === '' ? 'Pilih provinsi' : tempProfile.provinsi" :class="tempProfile.provinsi === '' ? 'text-gray-400' : 'text-[#4F4F4F]'"></span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                <button @click="tempProfile.provinsi = ''; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 first:rounded-t-xl">Pilih provinsi</button>
                                <button @click="tempProfile.provinsi = 'Aceh'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Aceh</button>
                                <button @click="tempProfile.provinsi = 'Sumatera Utara'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sumatera Utara</button>
                                <button @click="tempProfile.provinsi = 'Sumatera Barat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sumatera Barat</button>
                                <button @click="tempProfile.provinsi = 'Riau'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Riau</button>
                                <button @click="tempProfile.provinsi = 'Kepulauan Riau'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kepulauan Riau</button>
                                <button @click="tempProfile.provinsi = 'Jambi'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jambi</button>
                                <button @click="tempProfile.provinsi = 'Sumatera Selatan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sumatera Selatan</button>
                                <button @click="tempProfile.provinsi = 'Kepulauan Bangka Belitung'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kepulauan Bangka Belitung</button>
                                <button @click="tempProfile.provinsi = 'Bengkulu'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Bengkulu</button>
                                <button @click="tempProfile.provinsi = 'Lampung'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Lampung</button>
                                <button @click="tempProfile.provinsi = 'DKI Jakarta'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">DKI Jakarta</button>
                                <button @click="tempProfile.provinsi = 'Banten'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Banten</button>
                                <button @click="tempProfile.provinsi = 'Jawa Barat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jawa Barat</button>
                                <button @click="tempProfile.provinsi = 'Jawa Tengah'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jawa Tengah</button>
                                <button @click="tempProfile.provinsi = 'DI Yogyakarta'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">DI Yogyakarta</button>
                                <button @click="tempProfile.provinsi = 'Jawa Timur'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jawa Timur</button>
                                <button @click="tempProfile.provinsi = 'Bali'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Bali</button>
                                <button @click="tempProfile.provinsi = 'Nusa Tenggara Barat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Nusa Tenggara Barat</button>
                                <button @click="tempProfile.provinsi = 'Nusa Tenggara Timur'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Nusa Tenggara Timur</button>
                                <button @click="tempProfile.provinsi = 'Kalimantan Barat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kalimantan Barat</button>
                                <button @click="tempProfile.provinsi = 'Kalimantan Tengah'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kalimantan Tengah</button>
                                <button @click="tempProfile.provinsi = 'Kalimantan Selatan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kalimantan Selatan</button>
                                <button @click="tempProfile.provinsi = 'Kalimantan Timur'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kalimantan Timur</button>
                                <button @click="tempProfile.provinsi = 'Kalimantan Utara'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kalimantan Utara</button>
                                <button @click="tempProfile.provinsi = 'Sulawesi Utara'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sulawesi Utara</button>
                                <button @click="tempProfile.provinsi = 'Gorontalo'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Gorontalo</button>
                                <button @click="tempProfile.provinsi = 'Sulawesi Tengah'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sulawesi Tengah</button>
                                <button @click="tempProfile.provinsi = 'Sulawesi Barat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sulawesi Barat</button>
                                <button @click="tempProfile.provinsi = 'Sulawesi Selatan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sulawesi Selatan</button>
                                <button @click="tempProfile.provinsi = 'Sulawesi Tenggara'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Sulawesi Tenggara</button>
                                <button @click="tempProfile.provinsi = 'Maluku'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Maluku</button>
                                <button @click="tempProfile.provinsi = 'Maluku Utara'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Maluku Utara</button>
                                <button @click="tempProfile.provinsi = 'Papua'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Papua</button>
                                <button @click="tempProfile.provinsi = 'Papua Barat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Papua Barat</button>
                                <button @click="tempProfile.provinsi = 'Papua Tengah'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Papua Tengah</button>
                                <button @click="tempProfile.provinsi = 'Papua Pegunungan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Papua Pegunungan</button>
                                <button @click="tempProfile.provinsi = 'Papua Selatan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Papua Selatan</button>
                                <button @click="tempProfile.provinsi = 'Papua Barat Daya'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl">Papua Barat Daya</button>
                            </div>
                        </div>
                    </div>

                    <!-- Kota / Kabupaten -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Kota / Kabupaten</label>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" :class="open ? 'border-primary' : ''">
                                <span x-text="tempProfile.kota === '' ? 'Pilih kota/kabupaten' : tempProfile.kota" :class="tempProfile.kota === '' ? 'text-gray-400' : 'text-[#4F4F4F]'"></span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                <button @click="tempProfile.kota = ''; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 first:rounded-t-xl">Pilih kota/kabupaten</button>
                                <!-- Jawa Tengah -->
                                <div class="px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-50">Jawa Tengah</div>
                                <button @click="tempProfile.kota = 'Cilacap'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Cilacap</button>
                                <button @click="tempProfile.kota = 'Banyumas'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Banyumas</button>
                                <button @click="tempProfile.kota = 'Purbalingga'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Purbalingga</button>
                                <button @click="tempProfile.kota = 'Kebumen'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kebumen</button>
                                <button @click="tempProfile.kota = 'Magelang'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Magelang</button>
                                <button @click="tempProfile.kota = 'Semarang'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Semarang</button>
                                <button @click="tempProfile.kota = 'Kota Semarang'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kota Semarang</button>
                                <button @click="tempProfile.kota = 'Surakarta'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Surakarta</button>
                                <button @click="tempProfile.kota = 'Pekalongan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Pekalongan</button>
                                <button @click="tempProfile.kota = 'Tegal'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Tegal</button>
                                <!-- DKI Jakarta -->
                                <div class="px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-50">DKI Jakarta</div>
                                <button @click="tempProfile.kota = 'Jakarta Pusat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jakarta Pusat</button>
                                <button @click="tempProfile.kota = 'Jakarta Utara'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jakarta Utara</button>
                                <button @click="tempProfile.kota = 'Jakarta Barat'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jakarta Barat</button>
                                <button @click="tempProfile.kota = 'Jakarta Selatan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jakarta Selatan</button>
                                <button @click="tempProfile.kota = 'Jakarta Timur'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Jakarta Timur</button>
                                <!-- Jawa Barat -->
                                <div class="px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-50">Jawa Barat</div>
                                <button @click="tempProfile.kota = 'Bandung'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Bandung</button>
                                <button @click="tempProfile.kota = 'Kota Bandung'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kota Bandung</button>
                                <button @click="tempProfile.kota = 'Bekasi'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Bekasi</button>
                                <button @click="tempProfile.kota = 'Bogor'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Bogor</button>
                                <button @click="tempProfile.kota = 'Depok'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Depok</button>
                                <button @click="tempProfile.kota = 'Cirebon'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Cirebon</button>
                                <button @click="tempProfile.kota = 'Tasikmalaya'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Tasikmalaya</button>
                                <!-- Jawa Timur -->
                                <div class="px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-50">Jawa Timur</div>
                                <button @click="tempProfile.kota = 'Surabaya'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Surabaya</button>
                                <button @click="tempProfile.kota = 'Malang'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Malang</button>
                                <button @click="tempProfile.kota = 'Kota Malang'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kota Malang</button>
                                <button @click="tempProfile.kota = 'Kediri'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Kediri</button>
                                <button @click="tempProfile.kota = 'Blitar'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Blitar</button>
                                <button @click="tempProfile.kota = 'Mojokerto'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50">Mojokerto</button>
                                <button @click="tempProfile.kota = 'Pasuruan'; open = false;" type="button" class="block w-full text-left px-4 py-2 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl">Pasuruan</button>
                            </div>
                        </div>
                    </div>

                    <!-- Kode Pos -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Kode Pos</label>
                        <input 
                            type="text" 
                            x-model="tempProfile.kode_pos"
                            placeholder="Masukkan kode pos"
                            maxlength="5"
                            pattern="[0-9]*"
                            class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-6">
                    <button
                        @click="simpanDomisili()"
                        class="flex-1 btn-3d-green text-center"
                    >
                        Berikutnya
                    </button>
                    <button
                        @click="batalDomisili()"
                        class="flex-1 px-6 py-3 border border-[#C2C2C2] rounded-xl text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Ubah Kata Sandi -->
        <div
            x-show="showModalUbahPassword"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            @click.self="showModalUbahPassword = false"
            style="display: none;"
        >
            <div
                x-show="showModalUbahPassword"
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
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="!showPasswordLama" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPasswordLama" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
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
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <button
                            type="button"
                            @click="showPasswordBaru = !showPasswordBaru"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="!showPasswordBaru" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPasswordBaru" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
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
                            class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <button
                            type="button"
                            @click="showKonfirmasiPassword = !showKonfirmasiPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="!showKonfirmasiPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showKonfirmasiPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button
                        @click="showModalUbahPassword = false; passwordLama = ''; passwordBaru = ''; konfirmasiPassword = '';"
                        class="flex-1 btn-3d-green text-center h-12"
                    >
                        Simpan
                    </button>
                    <button
                        @click="showModalUbahPassword = false; passwordLama = ''; passwordBaru = ''; konfirmasiPassword = '';"
                        class="flex-1 h-12 border border-[#C2C2C2] rounded-xl text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
