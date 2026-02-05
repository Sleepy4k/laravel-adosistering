@extends('layouts.dashboard')

@section('title', 'Profil')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
@php
    $notesData = [];
    if ($user->details && $user->details->notes) {
        $notesData = json_decode($user->details->notes, true) ?? [];
    }
    $genderDisplay = match($user->details?->gender) {
        'male' => 'Laki-laki',
        'female' => 'Perempuan',
        default => ''
    };
@endphp

    <div class="max-w-7xl mx-auto" x-data="profilePage()" x-init="init()">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Profil</h1>
                <p class="text-sm text-gray-500" x-text="currentDate"></p>
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
                    <span class="text-sm text-gray-500" x-text="profile.alamat || '-'"></span>
                    <span class="text-sm text-gray-400" x-text="profile.nomor_whatsapp || '-'"></span>
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
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Nama Lengkap</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.nama_lengkap || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Nomor WhatsApp</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.nomor_whatsapp || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Email</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.email || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Jenis Kelamin</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.gender || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Alamat</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.alamat || '-'"></span>
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
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Negara</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.negara || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Provinsi</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.provinsi || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kota / Kabupaten</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.kota || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kode Pos</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.kode_pos || '-'"></span>
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
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Username</label>
                    <span class="text-base font-medium text-[#4F4F4F]" x-text="profile.username || '-'"></span>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-sm text-gray-400">Kata Sandi</label>
                    <span class="text-base font-medium text-[#4F4F4F]">**********</span>
                </div>
            </div>
        </div>
        @endcan

        <!-- Modal Informasi Pribadi -->
        <template x-if="showModalInformasiPribadi && tempProfile">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="batalInformasiPribadi()">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl mx-4 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-semibold text-[#4F4F4F]">Informasi Pribadi</h3>
                        <button @click="batalInformasiPribadi()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-6">
                        <div class="flex flex-col gap-2.5">
                            <label for="nama_lengkap" class="text-sm font-medium text-[#4F4F4F]">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" x-model="tempProfile.nama_lengkap" placeholder="Masukkan nama lengkap" autocomplete="name" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <label for="nomor_whatsapp" class="text-sm font-medium text-[#4F4F4F]">Nomor WhatsApp</label>
                            <input type="tel" id="nomor_whatsapp" name="nomor_whatsapp" x-model="tempProfile.nomor_whatsapp" placeholder="Masukkan nomor WhatsApp" autocomplete="tel" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <label for="email" class="text-sm font-medium text-[#4F4F4F]">Email</label>
                            <input type="email" id="email" name="email" x-model="tempProfile.email" placeholder="Masukkan email" autocomplete="email" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <label for="gender" class="text-sm font-medium text-[#4F4F4F]">Jenis Kelamin</label>
                            <div x-data="{ open: false }" class="relative">
                                <button type="button" @click="open = !open" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" :class="open ? 'border-primary ring-2 ring-primary' : ''">
                                    <span x-text="tempProfile.gender === '' ? 'Pilih jenis kelamin' : tempProfile.gender" :class="tempProfile.gender === '' ? 'text-gray-400' : ''"></span>
                                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg" style="display: none;">
                                    <button type="button" @click="tempProfile.gender = ''; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:bg-gray-50 first:rounded-t-xl transition-colors">Pilih jenis kelamin</button>
                                    <button type="button" @click="tempProfile.gender = 'Laki-laki'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">Laki-laki</button>
                                    <button type="button" @click="tempProfile.gender = 'Perempuan'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl transition-colors">Perempuan</button>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <label for="alamat" class="text-sm font-medium text-[#4F4F4F]">Alamat</label>
                            <input type="text" id="alamat" name="alamat" x-model="tempProfile.alamat" placeholder="Masukkan alamat" autocomplete="street-address" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                        </div>
                    </div>
                    <div class="flex gap-4 mt-8">
                        <button @click="simpanInformasiPribadi()" :disabled="isSaving" class="flex-1 btn-3d-green text-center h-11">
                            <span x-text="isSaving ? 'Menyimpan...' : 'Simpan'"></span>
                        </button>
                        <button @click="batalInformasiPribadi()" :disabled="isSaving" class="flex-1 h-11 border border-[#C2C2C2] rounded-xl text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal Domisili -->
        <template x-if="showModalDomisili && tempProfile">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="batalDomisili()">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-8 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-semibold text-[#4F4F4F]">Lokasi</h3>
                        <button @click="batalDomisili()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-6">
                        <div class="flex flex-col gap-2.5">
                            <label class="text-sm font-medium text-[#4F4F4F]">Negara</label>
                            <div x-data="{ open: false }" class="relative">
                                <button type="button" @click="open = !open" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" :class="open ? 'border-primary ring-2 ring-primary' : ''">
                                    <span x-text="tempProfile.negara === '' ? 'Pilih negara' : tempProfile.negara" :class="tempProfile.negara === '' ? 'text-gray-400' : ''"></span>
                                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg" style="display: none;">
                                    <button type="button" @click="tempProfile.negara = ''; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:bg-gray-50 first:rounded-t-xl transition-colors">Pilih negara</button>
                                    <button type="button" @click="tempProfile.negara = 'Indonesia'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">Indonesia</button>
                                    <button type="button" @click="tempProfile.negara = 'Malaysia'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">Malaysia</button>
                                    <button type="button" @click="tempProfile.negara = 'Singapura'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl transition-colors">Singapura</button>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <label class="text-sm font-medium text-[#4F4F4F]">Provinsi</label>
                            <div x-data="{ open: false }" class="relative">
                                <button type="button" @click="open = !open" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl flex items-center justify-between text-sm text-[#4F4F4F] hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" :class="open ? 'border-primary ring-2 ring-primary' : ''">
                                    <span x-text="tempProfile.provinsi === '' ? 'Pilih provinsi' : tempProfile.provinsi" :class="tempProfile.provinsi === '' ? 'text-gray-400' : ''"></span>
                                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-transition @click.away="open = false" class="absolute z-10 w-full mt-1 bg-white border border-[#C2C2C2] rounded-xl shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                                    <button type="button" @click="tempProfile.provinsi = ''; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:bg-gray-50 first:rounded-t-xl transition-colors">Pilih provinsi</button>
                                    <button type="button" @click="tempProfile.provinsi = 'Jawa Tengah'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">Jawa Tengah</button>
                                    <button type="button" @click="tempProfile.provinsi = 'Jawa Barat'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">Jawa Barat</button>
                                    <button type="button" @click="tempProfile.provinsi = 'Jawa Timur'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">Jawa Timur</button>
                                    <button type="button" @click="tempProfile.provinsi = 'DKI Jakarta'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">DKI Jakarta</button>
                                    <button type="button" @click="tempProfile.provinsi = 'DI Yogyakarta'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">DI Yogyakarta</button>
                                    <button type="button" @click="tempProfile.provinsi = 'Banten'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 transition-colors">Banten</button>
                                    <button type="button" @click="tempProfile.provinsi = 'Bali'; open = false;" class="block w-full text-left px-4 py-2.5 text-sm text-[#4F4F4F] hover:bg-gray-50 last:rounded-b-xl transition-colors">Bali</button>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <label for="kota" class="text-sm font-medium text-[#4F4F4F]">Kota / Kabupaten</label>
                            <input type="text" id="kota" name="kota" x-model="tempProfile.kota" placeholder="Masukkan kota/kabupaten" autocomplete="address-level2" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <label for="kode_pos" class="text-sm font-medium text-[#4F4F4F]">Kode Pos</label>
                            <input type="text" id="kode_pos" name="kode_pos" x-model="tempProfile.kode_pos" placeholder="Masukkan kode pos" maxlength="5" autocomplete="postal-code" class="w-full h-11 px-4 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                        </div>
                    </div>
                    <div class="flex gap-4 mt-8">
                        <button @click="simpanDomisili()" :disabled="isSaving" class="flex-1 btn-3d-green text-center h-11">
                            <span x-text="isSaving ? 'Menyimpan...' : 'Simpan'"></span>
                        </button>
                        <button @click="batalDomisili()" :disabled="isSaving" class="flex-1 h-11 border border-[#C2C2C2] rounded-xl text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal Ubah Password - Step 1: Input Password -->
        <template x-if="showModalUbahPassword">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="batalUbahPassword()">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-semibold text-[#4F4F4F]">Ubah Kata Sandi</h3>
                        <button @click="batalUbahPassword()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Kata Sandi Lama -->
                        <div class="flex flex-col gap-2.5">
                            <label for="current_password" class="text-sm font-medium text-[#4F4F4F]">Kata Sandi Lama</label>
                            <div class="relative">
                                <input 
                                    :type="showCurrentPassword ? 'text' : 'password'" 
                                    id="current_password" 
                                    name="current_password" 
                                    x-model="passwordForm.current_password" 
                                    placeholder="Masukkan kata sandi lama" 
                                    autocomplete="current-password" 
                                    class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                                />
                                <button 
                                    type="button" 
                                    @click="showCurrentPassword = !showCurrentPassword" 
                                    tabindex="-1"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
                                >
                                    <svg x-show="!showCurrentPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showCurrentPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Kata Sandi Baru -->
                        <div class="flex flex-col gap-2.5">
                            <label for="password" class="text-sm font-medium text-[#4F4F4F]">Kata Sandi Baru</label>
                            <div class="relative">
                                <input 
                                    :type="showNewPassword ? 'text' : 'password'" 
                                    id="password" 
                                    name="password" 
                                    x-model="passwordForm.password" 
                                    placeholder="Masukkan kata sandi baru" 
                                    autocomplete="new-password" 
                                    class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                                />
                                <button 
                                    type="button" 
                                    @click="showNewPassword = !showNewPassword" 
                                    tabindex="-1"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
                                >
                                    <svg x-show="!showNewPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showNewPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Konfirmasi Kata Sandi -->
                        <div class="flex flex-col gap-2.5">
                            <label for="password_confirmation" class="text-sm font-medium text-[#4F4F4F]">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <input 
                                    :type="showConfirmPassword ? 'text' : 'password'" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    x-model="passwordForm.password_confirmation" 
                                    placeholder="Konfirmasi kata sandi baru" 
                                    autocomplete="new-password" 
                                    class="w-full h-11 px-4 pr-12 bg-white border border-[#C2C2C2] rounded-xl text-sm text-[#4F4F4F] placeholder:text-gray-400 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                                />
                                <button 
                                    type="button" 
                                    @click="showConfirmPassword = !showConfirmPassword" 
                                    tabindex="-1"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
                                >
                                    <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showConfirmPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-4 mt-8">
                        <button @click="validatePasswordAndShowOtp()" :disabled="isSavingPassword" class="flex-1 btn-3d-green text-center h-11">
                            <span x-text="isSavingPassword ? 'Memproses...' : 'Lanjutkan'"></span>
                        </button>
                        <button @click="batalUbahPassword()" :disabled="isSavingPassword" class="flex-1 h-11 border border-[#C2C2C2] rounded-xl text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Modal OTP Verification - Step 2 -->
        <template x-if="showOtpModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showOtpModal = false">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-8 relative">
                    <!-- Close Button -->
                    <button @click="showOtpModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Header -->
                    <h3 class="text-xl font-semibold text-[#4F4F4F] mb-8">Ubah Kata Sandi</h3>
                    
                    <!-- Title -->
                    <h2 class="text-xl font-bold text-[#4F4F4F] text-center mb-3">Masukkan Kode OTP</h2>
                    
                    <!-- Description -->
                    <p class="text-sm text-gray-500 text-center mb-1">Masukkan kode OTP yang telah dikirim ke email :</p>
                    <p class="text-sm font-semibold text-[#4F4F4F] text-center mb-8" x-text="profile.email"></p>

                    <!-- OTP Input -->
                    <div class="flex justify-center gap-4 mb-8">
                        <template x-for="(digit, index) in otpDigits" :key="index">
                            <input 
                                type="text" 
                                maxlength="1" 
                                x-model="otpDigits[index]"
                                @input="handleOtpInput($event, index)"
                                @keydown.backspace="handleOtpBackspace($event, index)"
                                @paste="handleOtpPaste($event)"
                                :id="'otp-' + index"
                                class="w-14 h-16 text-center text-xl font-bold border border-[#C2C2C2] rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            />
                        </template>
                    </div>

                    <!-- Resend OTP -->
                    <div class="text-center mb-8">
                        <p class="text-sm text-gray-500 mb-2">Belum menerima OTP?</p>
                        <button 
                            type="button"
                            @click="resendOtp()"
                            :disabled="resendCooldown > 0"
                            class="text-sm text-primary hover:text-primary/80 font-medium disabled:text-gray-400 disabled:cursor-not-allowed"
                        >
                            <span x-show="resendCooldown <= 0">Kirim Ulang OTP</span>
                            <span x-show="resendCooldown > 0">Kirim ulang dalam <span x-text="resendCooldown"></span>s</span>
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="button"
                        @click="verifyOtpAndSavePassword()"
                        :disabled="otpDigits.join('').length !== 4 || isSavingPassword"
                        class="btn-3d-green w-full h-12 text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-text="isSavingPassword ? 'Menyimpan...' : 'Verifikasi & Simpan'"></span>
                    </button>
                </div>
            </div>
        </template>

        <!-- Modal Success -->
        <template x-if="showSuccess">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 shadow-xl text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#4F4F4F] mb-2">Berhasil!</h3>
                    <p class="text-sm text-gray-500 mb-4" x-text="successMessage"></p>
                    <button type="button" @click="showSuccess = false" class="w-full btn-3d-green text-white font-semibold rounded-xl h-10">OK</button>
                </div>
            </div>
        </template>

        <!-- Modal Failed -->
        <template x-if="showFailed">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 shadow-xl text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#4F4F4F] mb-2">Gagal!</h3>
                    <p class="text-sm text-gray-500 mb-4" x-text="failedMessage"></p>
                    <button type="button" @click="showFailed = false" class="w-full bg-gray-100 hover:bg-gray-200 text-[#4F4F4F] font-semibold rounded-xl h-10 transition-colors">Tutup</button>
                </div>
            </div>
        </template>
    </div>
@endsection

@push('scripts')
<script>
function profilePage() {
    const notesJson = @json($notesData ?? []);
    
    return {
        currentDate: '',
        showModalInformasiPribadi: false,
        showModalDomisili: false,
        showModalUbahPassword: false,
        showOtpModal: false,
        showSuccess: false,
        showFailed: false,
        successMessage: '',
        failedMessage: '',
        isSaving: false,
        isSavingPassword: false,
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,
        profile: {
            nama_lengkap: @json($user->name ?? ''),
            nomor_whatsapp: @json($user->phone ?? ''),
            email: @json($user->email ?? ''),
            gender: @json($genderDisplay ?? ''),
            alamat: @json($user->details?->address ?? ''),
            negara: notesJson.negara || 'Indonesia',
            provinsi: notesJson.provinsi || '',
            kota: @json($user->details?->domicile ?? ''),
            kode_pos: notesJson.kode_pos || '',
            username: @json($user->name ?? '')
        },
        tempProfile: null,
        passwordForm: { current_password: '', password: '', password_confirmation: '' },
        otpDigits: ['', '', '', ''],
        resendCooldown: 0,
        resendTimer: null,
        
        init() {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const now = new Date();
            this.currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
        },
        
        openModalInformasiPribadi() {
            this.tempProfile = JSON.parse(JSON.stringify(this.profile));
            this.showModalInformasiPribadi = true;
        },
        
        openModalDomisili() {
            this.tempProfile = JSON.parse(JSON.stringify(this.profile));
            this.showModalDomisili = true;
        },
        
        batalInformasiPribadi() {
            this.showModalInformasiPribadi = false;
            setTimeout(() => { this.tempProfile = null; }, 100);
        },
        
        batalDomisili() {
            this.showModalDomisili = false;
            setTimeout(() => { this.tempProfile = null; }, 100);
        },
        
        async simpanInformasiPribadi() {
            this.isSaving = true;
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Helper to filter out null/empty values
                const filterNull = (value) => {
                    if (value === null || value === undefined || value === '' || value === 'null') {
                        return null;
                    }
                    return value;
                };
                
                const response = await fetch('/profile/basic', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: filterNull(this.tempProfile.nama_lengkap),
                        email: filterNull(this.tempProfile.email),
                        phone: filterNull(this.tempProfile.nomor_whatsapp),
                        gender: this.tempProfile.gender === 'Laki-laki' ? 'male' : (this.tempProfile.gender === 'Perempuan' ? 'female' : null),
                        address: filterNull(this.tempProfile.alamat)
                    })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    this.profile.nama_lengkap = this.tempProfile.nama_lengkap;
                    this.profile.nomor_whatsapp = this.tempProfile.nomor_whatsapp;
                    this.profile.email = this.tempProfile.email;
                    this.profile.gender = this.tempProfile.gender;
                    this.profile.alamat = this.tempProfile.alamat;
                    this.showModalInformasiPribadi = false;
                    setTimeout(() => { this.tempProfile = null; }, 100);
                    this.successMessage = data.message || 'Informasi pribadi berhasil diperbarui.';
                    this.showSuccess = true;
                } else {
                    this.failedMessage = data.message || 'Gagal memperbarui informasi pribadi.';
                    this.showFailed = true;
                }
            } catch (error) {
                console.error('Error:', error);
                this.failedMessage = 'Terjadi kesalahan jaringan.';
                this.showFailed = true;
            } finally {
                this.isSaving = false;
            }
        },
        
        async simpanDomisili() {
            this.isSaving = true;
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                // Helper to filter out null/empty values
                const filterNull = (value) => {
                    if (value === null || value === undefined || value === '' || value === 'null') {
                        return null;
                    }
                    return value;
                };
                
                const response = await fetch('/profile/other', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        domicile: filterNull(this.tempProfile.kota),
                        negara: filterNull(this.tempProfile.negara),
                        provinsi: filterNull(this.tempProfile.provinsi),
                        kode_pos: filterNull(this.tempProfile.kode_pos)
                    })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    this.profile.negara = this.tempProfile.negara;
                    this.profile.provinsi = this.tempProfile.provinsi;
                    this.profile.kota = this.tempProfile.kota;
                    this.profile.kode_pos = this.tempProfile.kode_pos;
                    this.showModalDomisili = false;
                    setTimeout(() => { this.tempProfile = null; }, 100);
                    this.successMessage = data.message || 'Domisili berhasil diperbarui.';
                    this.showSuccess = true;
                } else {
                    this.failedMessage = data.message || 'Gagal memperbarui domisili.';
                    this.showFailed = true;
                }
            } catch (error) {
                console.error('Error:', error);
                this.failedMessage = 'Terjadi kesalahan jaringan.';
                this.showFailed = true;
            } finally {
                this.isSaving = false;
            }
        },
        
        // Password Change Flow Methods
        batalUbahPassword() {
            this.showModalUbahPassword = false;
            this.passwordForm = { current_password: '', password: '', password_confirmation: '' };
        },
        
        async validatePasswordAndShowOtp() {
            // Validate current password is not empty
            if (!this.passwordForm.current_password) {
                this.failedMessage = 'Kata sandi lama harus diisi.';
                this.showFailed = true;
                return;
            }
            // Validate new password
            if (!this.passwordForm.password) {
                this.failedMessage = 'Kata sandi baru harus diisi.';
                this.showFailed = true;
                return;
            }
            if (this.passwordForm.password.length < 8) {
                this.failedMessage = 'Kata sandi baru minimal 8 karakter.';
                this.showFailed = true;
                return;
            }
            if (this.passwordForm.password !== this.passwordForm.password_confirmation) {
                this.failedMessage = 'Konfirmasi kata sandi tidak cocok.';
                this.showFailed = true;
                return;
            }
            if (this.passwordForm.current_password === this.passwordForm.password) {
                this.failedMessage = 'Kata sandi baru tidak boleh sama dengan kata sandi lama.';
                this.showFailed = true;
                return;
            }
            
            // VALIDATE CURRENT PASSWORD WITH BACKEND FIRST
            this.isSavingPassword = true;
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('/profile/validate-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: this.passwordForm.current_password
                    })
                });
                const data = await response.json();
                
                if (!response.ok || !data.success) {
                    this.failedMessage = data.message || 'Kata sandi saat ini tidak sesuai.';
                    this.showFailed = true;
                    this.isSavingPassword = false;
                    return;
                }
                
                // If password is valid, close password modal and show OTP modal
                this.showModalUbahPassword = false;
                this.otpDigits = ['', '', '', ''];
                this.showOtpModal = true;
                this.startResendCooldown();
                
                // Focus first OTP input
                setTimeout(() => {
                    document.getElementById('otp-0')?.focus();
                }, 100);
                
            } catch (error) {
                console.error('Error:', error);
                this.failedMessage = 'Terjadi kesalahan saat memvalidasi kata sandi.';
                this.showFailed = true;
            } finally {
                this.isSavingPassword = false;
            }
        },
        
        // OTP Input Handlers
        handleOtpInput(event, index) {
            const value = event.target.value.replace(/[^0-9]/g, '');
            this.otpDigits[index] = value.slice(-1);
            
            if (value && index < 3) {
                document.getElementById('otp-' + (index + 1))?.focus();
            }
        },
        
        handleOtpBackspace(event, index) {
            if (!this.otpDigits[index] && index > 0) {
                document.getElementById('otp-' + (index - 1))?.focus();
            }
        },
        
        handleOtpPaste(event) {
            event.preventDefault();
            const pastedData = (event.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 4);
            for (let i = 0; i < 4; i++) {
                this.otpDigits[i] = pastedData[i] || '';
            }
        },
        
        startResendCooldown() {
            this.resendCooldown = 60;
            if (this.resendTimer) clearInterval(this.resendTimer);
            this.resendTimer = setInterval(() => {
                this.resendCooldown--;
                if (this.resendCooldown <= 0) {
                    clearInterval(this.resendTimer);
                }
            }, 1000);
        },
        
        resendOtp() {
            if (this.resendCooldown > 0) return;
            
            // TODO: Implement real OTP resend with backend API
            // This should call an endpoint to send new OTP to user's email
            // Example: POST /profile/resend-otp
            
            this.startResendCooldown();
            this.successMessage = 'Kode OTP baru telah dikirim ke email Anda.';
            this.showSuccess = true;
        },
        
        async verifyOtpAndSavePassword() {
            const otp = this.otpDigits.join('');
            
            // Validasi OTP
            if (!otp || otp.length !== 4) {
                this.failedMessage = 'Kode OTP harus 4 digit.';
                this.showFailed = true;
                return;
            }
            
            // TODO: Implement real OTP verification with backend
            // For now, using hardcoded OTP for testing: 1234
            // Replace this with actual API call to verify OTP
            if (otp !== '1234') {
                this.failedMessage = 'Kode OTP tidak valid atau sudah kadaluarsa.';
                this.showFailed = true;
                return;
            }
            
            this.isSavingPassword = true;
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch('/profile/credential', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: this.passwordForm.current_password,
                        password: this.passwordForm.password,
                        password_confirmation: this.passwordForm.password_confirmation
                    })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    this.showOtpModal = false;
                    this.passwordForm = { current_password: '', password: '', password_confirmation: '' };
                    this.otpDigits = ['', '', '', ''];
                    this.successMessage = 'Kata sandi berhasil diubah.';
                    this.showSuccess = true;
                } else {
                    this.failedMessage = data.message || 'Gagal mengubah kata sandi.';
                    this.showFailed = true;
                }
            } catch (error) {
                console.error('Error:', error);
                this.failedMessage = 'Terjadi kesalahan jaringan.';
                this.showFailed = true;
            } finally {
                this.isSavingPassword = false;
            }
        }
    };
}
</script>
@endpush