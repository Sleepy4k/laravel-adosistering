@extends('layouts.superadmin')

@section('title', 'Data Pengguna')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="editPengguna()">

        <!-- Success Notification Modal -->
        <div x-show="showSuccessModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="success-modal-title" 
            role="dialog" 
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showSuccessModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/50 transition-opacity"
                    @click="showSuccessModal = false"></div>

                <!-- Hidden element for centering -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showSuccessModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative inline-block align-bottom bg-white rounded-2xl px-6 pt-5 pb-6 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    
                    <div class="text-center">
                        <!-- Success Icon -->
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
                            <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-[#4F4F4F] mb-2" x-text="successTitle"></h3>
                        <p class="text-sm text-gray-500 mb-6" x-text="successMessage"></p>
                    </div>

                    <button type="button" 
                        @click="showSuccessModal = false"
                        class="btn-3d-green w-full text-center justify-center">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6" role="alert">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Ada error dalam form:</p>
                        <ul class="text-sm mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header Section with Title and Action Buttons -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Data Pengguna</h1>
                <div class="flex items-center gap-3">
                    <!-- Kirim Kredensial WA Button -->
                    <button type="button" 
                        @click="showKredensialModal = true"
                        class="btn-3d-green inline-flex items-center gap-4">
                        <img src="{{ asset('assets/icons/chat.svg') }}" alt="Chat Icon" class="w-5 h-5">
                        <span>Kirim Kredensial (WA)</span>
                    </button>
                    
                    <!-- Edit Button -->
                    <button type="button" 
                        @click="isEditing = !isEditing"
                        :class="isEditing ? 'btn-3d-blue' : 'btn-3d-red'"
                        class="inline-flex items-center gap-4">
                        <img src="{{ asset('assets/icons/edit.svg') }}" alt="Edit Icon" class="w-5 h-5">
                        <span x-text="isEditing ? 'Edit' : 'Batal Edit'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div>
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('superadmin.dashboard') }}" class="text-base hover:text-primary-color transition-colors">Beranda</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-base text-gray-400">Lihat Data Pengguna</span>
            </nav>
        </div>

        <!-- Form Container -->
        <form action="{{ route('superadmin.users.update', $user['id'] ?? 1) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Card Data Wajib -->
            <div class="bg-white rounded-2xl border border-[#C2C2C2] overflow-hidden">
                <!-- Header Data Wajib -->
                <div class="flex items-center justify-between px-6 py-4 cursor-pointer"
                    @click="dataWajibOpen = !dataWajibOpen">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="{{ asset('assets/icons/document.svg') }}" alt="Document Icon" class="w-5 h-6">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#4F4F4F]">Data Wajib</h3>
                            <p class="text-sm text-gray-500">Lengkapi data yang wajib diisi</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="dataWajibOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <!-- Content Data Wajib -->
                <div x-show="dataWajibOpen" x-transition:enter="transition-all duration-300 ease-out"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen"
                    x-transition:leave="transition-all duration-200 ease-in"
                    x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 space-y-6 overflow-hidden">

                    <!-- Info Text -->
                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Apabila menginput pengguna baru, silahkan isi formulir berikut</p>
                    </div>

                    <!-- Form Fields Data Wajib -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-16 mb-6">
                        <!-- Nama Pengguna -->
                        <div>
                            <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                            <input type="text" id="nama_pengguna" name="nama_pengguna" x-model="formData.nama_pengguna"
                                placeholder="Masukkan nama pengguna"
                                class="form-input @error('nama_pengguna') error @enderror"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''"
                                required>
                            <p class="form-helper">Nama akan ditampilkan di card pengguna pada dashboard</p>
                            @error('nama_pengguna')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor WhatsApp -->
                        <div>
                            <label for="nomor_whatsapp" class="form-label">Nomor WhatsApp</label>
                            <input type="text" id="nomor_whatsapp" name="nomor_whatsapp"
                                x-model="formData.nomor_whatsapp" placeholder="Masukkan nomor WhatsApp"
                                class="form-input @error('nomor_whatsapp') error @enderror"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''"
                                required>
                            <p class="form-helper">Nomor untuk kontak dan WhatsApp</p>
                            @error('nomor_whatsapp')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" x-model="formData.email"
                                placeholder="Masukkan email pengguna" class="form-input @error('email') error @enderror"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''"
                                required>
                            <p class="form-helper">Email untuk komunikasi digital</p>
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Pengguna -->
                        <div>
                            <label for="jenis_pengguna" class="form-label">Jenis Pengguna</label>
                            <input type="hidden" name="jenis_pengguna" x-model="formData.jenis_pengguna">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="isEditing && (open = !open)" type="button"
                                    class="form-input form-select text-left flex items-center justify-between @error('jenis_pengguna') error @enderror"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                                    <span x-text="formData.jenis_pengguna || 'Pilih jenis pengguna'"
                                        :class="formData.jenis_pengguna ? 'text-gray-700' : 'text-gray-400'"></span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open && isEditing" x-transition @click.away="open = false"
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg"
                                    style="display: none;">
                                    <button @click="formData.jenis_pengguna = 'Individu'; open = false" type="button"
                                        class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-xl">
                                        Individu
                                    </button>
                                    <button @click="formData.jenis_pengguna = 'Institusi/Lembaga'; open = false"
                                        type="button"
                                        class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 last:rounded-b-xl">
                                        Institusi/Lembaga
                                    </button>
                                </div>
                            </div>
                            <p class="form-helper">Jenis pengguna berdasarkan kategori</p>
                            @error('jenis_pengguna')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Data Opsional -->
            <div class="bg-white rounded-2xl border border-[#C2C2C2] overflow-hidden">
                <!-- Header Data Opsional -->
                <div class="flex items-center justify-between px-6 py-4 cursor-pointer"
                    @click="dataOpsionalOpen = !dataOpsionalOpen">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="{{ asset('assets/icons/document-optional.svg') }}" alt="Document Optional Icon"
                                class="w-5 h-6">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#4F4F4F]">Data Opsional</h3>
                            <p class="text-sm text-gray-500">Field ini opsional. Anda bisa mengisi sekarang atau nanti saat
                                mengedit pengguna</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="dataOpsionalOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <!-- Content Data Opsional -->
                <div x-show="dataOpsionalOpen" x-transition:enter="transition-all duration-300 ease-out"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen"
                    x-transition:leave="transition-all duration-200 ease-in"
                    x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 space-y-6 overflow-hidden">

                    <!-- Form Fields Data Opsional -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Panggilan -->
                        <div>
                            <label for="nama_panggilan" class="form-label">Nama Panggilan</label>
                            <input type="text" id="nama_panggilan" name="nama_panggilan"
                                x-model="formData.nama_panggilan" placeholder="Panggilan akrab" 
                                class="form-input"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <input type="hidden" name="jenis_kelamin" x-model="formData.jenis_kelamin">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="isEditing && (open = !open)" type="button"
                                    class="form-input form-select text-left flex items-center justify-between"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                                    <span x-text="formData.jenis_kelamin || 'Pilih jenis kelamin'"
                                        :class="formData.jenis_kelamin ? 'text-gray-700' : 'text-gray-400'"></span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open && isEditing" x-transition @click.away="open = false"
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg"
                                    style="display: none;">
                                    <button @click="formData.jenis_kelamin = 'Laki-laki'; open = false" type="button"
                                        class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-xl">
                                        Laki-laki
                                    </button>
                                    <button @click="formData.jenis_kelamin = 'Perempuan'; open = false" type="button"
                                        class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 last:rounded-b-xl">
                                        Perempuan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                x-model="formData.tanggal_lahir" 
                                class="form-input"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                        </div>

                        <!-- No. HP / WhatsApp Lain -->
                        <div>
                            <label for="hp_lain" class="form-label">No. HP / WhatsApp Lain</label>
                            <input type="text" id="hp_lain" name="hp_lain" x-model="formData.hp_lain"
                                placeholder="08xxxxxxxxx" 
                                class="form-input"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                        </div>

                        <!-- Pekerjaan/Profesi -->
                        <div>
                            <label for="pekerjaan" class="form-label">Pekerjaan/Profesi</label>
                            <input type="text" id="pekerjaan" name="pekerjaan" x-model="formData.pekerjaan"
                                placeholder="Masukkan pekerjaan/profesi" 
                                class="form-input"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                        </div>

                        <!-- Wilayah/Domisili -->
                        <div>
                            <label for="wilayah" class="form-label">Wilayah/Domisili</label>
                            <input type="text" id="wilayah" name="wilayah" x-model="formData.wilayah"
                                placeholder="Masukkan wilayah/domisili" 
                                class="form-input"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="mt-6">
                        <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                        <textarea id="alamat_lengkap" name="alamat_lengkap" x-model="formData.alamat_lengkap" rows="4"
                            placeholder="Masukkan alamat lengkap..." 
                            class="form-input form-textarea resize-none"
                            :disabled="!isEditing"
                            :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''"></textarea>
                    </div>

                    <!-- Catatan Internal -->
                    <div class="mt-6">
                        <label for="catatan_internal" class="form-label">Catatan Internal</label>
                        <textarea id="catatan_internal" name="catatan_internal" x-model="formData.catatan_internal" rows="4"
                            placeholder="Masukkan catatan internal (hanya untuk admin)..." 
                            class="form-input form-textarea resize-none"
                            :disabled="!isEditing"
                            :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''"></textarea>
                        <p class="form-helper">Catatan ini hanya dapat dilihat oleh admin</p>
                    </div>
                </div>
            </div>

            <!-- Card Kredensial -->
            <div class="bg-white rounded-2xl border border-[#C2C2C2] overflow-hidden">
                <!-- Header Kredensial -->
                <div class="flex items-center justify-between px-6 py-4 cursor-pointer"
                    @click="kredensialOpen = !kredensialOpen">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="{{ asset('assets/icons/kredensial.svg') }}" alt="Kredensial Icon" class="w-5 h-6">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#4F4F4F]">Kredensial</h3>
                            <p class="text-sm text-gray-500">Data kredensial pengguna</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        :class="kredensialOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <!-- Content Kredensial -->
                <div x-show="kredensialOpen" x-transition:enter="transition-all duration-300 ease-out"
                    x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen"
                    x-transition:leave="transition-all duration-200 ease-in"
                    x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0"
                    class="px-6 pb-6 space-y-6 overflow-hidden">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Username -->
                        <div>
                            <label for="username" class="form-label">Username</label>
                            <div class="relative">
                                <input type="text" id="username" name="username" x-model="formData.username"
                                    placeholder="Username pengguna untuk mengakses alat" 
                                    class="form-input pr-12"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''" readonly>
                            </div>
                            <p class="form-helper">Username pengguna untuk mengakses alat</p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" 
                                    x-model="formData.password"
                                    placeholder="Masukkan password baru"
                                    class="form-input pr-12 @error('password') error @enderror"
                                    :disabled="!isEditing"
                                    :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''">
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                                    :class="!isEditing ? 'cursor-not-allowed' : ''">
                                    <span class="relative w-5 h-5">
                                        <img x-show="!showPassword" src="{{ asset('assets/icons/eye_on.svg') }}" alt="Show Password" class="w-5 h-5 absolute inset-0">
                                        <img x-show="showPassword" src="{{ asset('assets/icons/eye_off.svg') }}" alt="Hide Password" class="w-5 h-5 absolute inset-0">
                                    </span>
                                </button>
                            </div>
                            <p class="form-helper">Password untuk login pengguna (min. 8 karakter)</p>
                            @error('password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- API Key -->
                    <div class="mt-6">
                        <label for="api_key" class="form-label">API Key</label>
                        <div class="relative">
                            <input type="text" id="api_key" name="api_key" x-model="formData.api_key"
                                placeholder="b4c0f8a3-79f9-4a5f-8d8e-1a5b2c3d4e5f" 
                                class="form-input pr-12"
                                :disabled="!isEditing"
                                :class="!isEditing ? 'bg-gray-50 cursor-not-allowed' : ''" readonly>
                            <button type="button" @click="copyApiKey()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <img src="{{ asset('assets/icons/copy.svg') }}" alt="Copy" class="w-5 h-5">
                            </button>
                        </div>
                        <p class="form-helper">API Key untuk diterapkan pada alat pengguna</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Show only when editing) -->
            <div x-show="isEditing" x-transition class="flex items-center justify-start space-x-3 pt-4">
                <button type="submit"
                    class="btn-3d-green px-8 py-3 font-medium rounded-xl transition-colors duration-200">
                    Simpan Perubahan
                </button>
                <button type="button" @click="resetForm()"
                    class="px-8 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </button>
            </div>
        </form>

        <!-- Modal Preview Kredensial WA -->
        <div x-show="showKredensialModal" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

            <!-- Overlay -->
            <div class="fixed inset-0 bg-black/30" @click="showKredensialModal = false"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl" @click.away="showKredensialModal = false">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Preview Kredensial WhatsApp</h3>
                        <button @click="showKredensialModal = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body - WhatsApp Message Preview -->
                    <div class="p-6">
                        <div class="bg-[#E7FFDB] rounded-xl p-4 shadow-sm">
                            <div class="text-sm text-gray-800 space-y-1">
                                <p class="font-semibold">Ini adalah Kredensial Akses Perangkat IoT Adosistering</p>
                                <p>ℹ️ Nama: <span x-text="formData.nama_pengguna"></span></p>
                                <p>🔗 URL Login: <span class="text-blue-600">{{ config('app.url', 'Adosistering.com') }}</span></p>
                                <p>👤 Username: <span x-text="kredensial.username"></span></p>
                                <p>🔒 Password: <span x-text="kredensial.password"></span></p>
                                <p class="pt-2">🔑 API Key: <span x-text="kredensial.apiKey || '-'"></span></p>
                                <p>📍 Domisili: <span x-text="kredensial.domisili || '-'"></span></p>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-500 mt-4 text-center">
                            Pesan ini akan dikirim ke nomor <strong x-text="formData.nomor_whatsapp"></strong>
                        </p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex items-center justify-end gap-3">
                        <button @click="showKredensialModal = false" type="button"
                            class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            Batal
                        </button>
                        <form action="{{ route('superadmin.users.send-credential', $user['id'] ?? 1) }}" method="POST" class="inline">
                            @csrf
                            <!-- Hidden fields for kredensial data -->
                            <input type="hidden" name="nama" x-bind:value="formData.nama_pengguna">
                            <input type="hidden" name="nomor_whatsapp" x-bind:value="formData.nomor_whatsapp">
                            <input type="hidden" name="username" x-bind:value="kredensial.username">
                            <input type="hidden" name="password" x-bind:value="kredensial.password">
                            <input type="hidden" name="api_key" x-bind:value="kredensial.apiKey">
                            <input type="hidden" name="domisili" x-bind:value="kredensial.domisili">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-[#25D366] hover:bg-[#20BD5A] text-white font-medium rounded-xl transition-colors duration-200 gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Kirim via WhatsApp
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Script -->
    <script>
        function editPengguna() {
            return {
                isEditing: false,
                dataWajibOpen: true,
                dataOpsionalOpen: false,
                kredensialOpen: false,
                showKredensialModal: false,
                showPassword: false,
                showSuccessModal: false,
                successTitle: '',
                successMessage: '',
                
                // Data dari backend (akan di-populate dari server)
                formData: {
                    nama_pengguna: '{{ $user["nama_pengguna"] ?? "Muchtarom" }}',
                    nomor_whatsapp: '{{ $user["nomor_whatsapp"] ?? "081234567890" }}',
                    email: '{{ $user["email"] ?? "muchtarom@gmail.com" }}',
                    jenis_pengguna: '{{ $user["jenis_pengguna"] ?? "Individu" }}',
                    password: '',
                    nama_panggilan: '{{ $user["nama_panggilan"] ?? "" }}',
                    jenis_kelamin: '{{ $user["jenis_kelamin"] ?? "" }}',
                    tanggal_lahir: '{{ $user["tanggal_lahir"] ?? "" }}',
                    hp_lain: '{{ $user["hp_lain"] ?? "" }}',
                    alamat_lengkap: '{{ $user["alamat_lengkap"] ?? "" }}',
                    pekerjaan: '{{ $user["pekerjaan"] ?? "" }}',
                    wilayah: '{{ $user["wilayah"] ?? "" }}',
                    catatan_internal: '{{ $user["catatan_internal"] ?? "" }}',
                    username: '{{ $user["username"] ?? "muchtarom01" }}',
                    api_key: '{{ $user["api_key"] ?? "" }}'
                },
                
                // Backup data untuk reset
                originalData: null,
                
                // Kredensial data untuk WhatsApp
                kredensial: {
                    username: '{{ $user["username"] ?? "muchtarom01" }}',
                    password: '{{ $user["password"] ?? "muchtarom123" }}',
                    apiKey: '{{ $user["api_key"] ?? "" }}',
                    domisili: '{{ $user["domisili"] ?? "Purbalingga" }}'
                },

                init() {
                    // Simpan original data untuk reset
                    this.originalData = JSON.parse(JSON.stringify(this.formData));
                    
                    // Check for session success from Laravel
                    @if(session('success'))
                        this.successTitle = 'Berhasil!';
                        this.successMessage = '{{ session('success') }}';
                        this.showSuccessModal = true;
                    @endif
                    
                    @if(session('wa_sent'))
                        this.successTitle = 'Berhasil!';
                        this.successMessage = 'Kredensial berhasil dikirim via WhatsApp!';
                        this.showSuccessModal = true;
                    @endif
                },

                resetForm() {
                    this.formData = JSON.parse(JSON.stringify(this.originalData));
                },

                copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        alert('Berhasil disalin!');
                    }).catch(err => {
                        console.error('Gagal menyalin: ', err);
                    });
                },

                copyApiKey() {
                    this.copyToClipboard(this.formData.api_key);
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
