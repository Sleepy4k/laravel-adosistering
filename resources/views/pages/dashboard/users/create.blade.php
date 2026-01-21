@extends('layouts.dashboard')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="tambahPengguna()">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6" role="alert">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

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
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Tambah Pengguna</h1>
                <div class="flex items-center gap-3">
                    <!-- Kirim Kredensial WA Button -->
                    <button type="button" @click="showKredensialModal = true"
                        class="btn-3d-green inline-flex items-center gap-4">
                        <img src="{{ asset('assets/icons/chat.svg') }}" alt="Chat Icon" class="w-5 h-5">
                        <span>Kirim Kredensial (WA)</span>
                    </button>

                    <!-- Edit Button (toggle form editability for create) -->
                    <button type="button" @click="isEditing = !isEditing" :class="isEditing ? 'btn-3d-blue' : 'btn-3d-red'"
                        class="inline-flex items-center gap-4">
                        <img src="{{ asset('assets/icons/edit.svg') }}" alt="Edit Icon" class="w-5 h-5">
                        <span x-text="isEditing ? 'Edit' : 'Batal Edit'"></span>
                    </button>
                </div>
            </div>
        </div>
        <div>
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('home') }}"
                    class="text-base hover:text-primary-color transition-colors">Beranda</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-base text-gray-400">Tambah Pengguna</span>
            </nav>
        </div>

        <!-- Form Container - Card Data Wajib -->
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

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

                    <!-- Pilih Pengguna Button -->
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">Apabila menginput pengguna baru, silahkan isi formulir berikut
                        </p>
                        <button @click="showUserModal = true" type="button"
                            class="btn-3d-green inline-flex items-center px-6 py-3 font-medium rounded-xl transition-colors duration-200">
                            <img src="{{ asset('assets/icons/person.svg') }}" alt="Person Icon" class="w-4 h-4 mr-2">
                            Pilih Pengguna
                        </button>
                    </div>

                    <!-- Form Fields Data Wajib -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-16">
                        <!-- Nama Pengguna -->
                        <div>
                            <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                            <input type="text" id="nama_pengguna" name="nama_pengguna" x-model="formData.nama_pengguna"
                                placeholder="Masukkan nama pengguna"
                                class="form-input @error('nama_pengguna') error @enderror" required>
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
                                class="form-input @error('nomor_whatsapp') error @enderror" required>
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
                                <button @click="open = !open" type="button"
                                    class="form-input form-select text-left flex items-center justify-between @error('jenis_pengguna') error @enderror">
                                    <span x-text="formData.jenis_pengguna || 'Pilih jenis pengguna'"
                                        :class="formData.jenis_pengguna ? 'text-gray-700' : 'text-gray-400'"></span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition @click.away="open = false"
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
                                x-model="formData.nama_panggilan" placeholder="Panggilan akrab" class="form-input">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <input type="hidden" name="jenis_kelamin" x-model="formData.jenis_kelamin">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                    class="form-input form-select text-left flex items-center justify-between">
                                    <span x-text="formData.jenis_kelamin || 'Pilih jenis kelamin'"
                                        :class="formData.jenis_kelamin ? 'text-gray-700' : 'text-gray-400'"></span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform"
                                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition @click.away="open = false"
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
                                x-model="formData.tanggal_lahir" class="form-input">
                        </div>

                        <!-- No. HP / WhatsApp Lain -->
                        <div>
                            <label for="hp_lain" class="form-label">No. HP / WhatsApp Lain</label>
                            <input type="text" id="hp_lain" name="hp_lain" x-model="formData.hp_lain"
                                placeholder="08xxxxxxxxx" class="form-input">
                        </div>

                        <!-- Pekerjaan/Profesi -->
                        <div>
                            <label for="pekerjaan" class="form-label">Pekerjaan/Profesi</label>
                            <input type="text" id="pekerjaan" name="pekerjaan" x-model="formData.pekerjaan"
                                placeholder="Masukkan pekerjaan/profesi" class="form-input">
                        </div>

                        <!-- Wilayah/Domisili -->
                        <div>
                            <label for="wilayah" class="form-label">Wilayah/Domisili</label>
                            <input type="text" id="wilayah" name="wilayah" x-model="formData.wilayah"
                                placeholder="Masukkan wilayah/domisili" class="form-input">
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="mt-6">
                        <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                        <textarea id="alamat_lengkap" name="alamat_lengkap" x-model="formData.alamat_lengkap" rows="4"
                            placeholder="Masukkan alamat lengkap..." class="form-input form-textarea resize-none"></textarea>
                    </div>

                    <!-- Catatan Internal -->
                    <div class="mt-6">
                        <label for="catatan_internal" class="form-label">Catatan Internal</label>
                        <textarea id="catatan_internal" name="catatan_internal" x-model="formData.catatan_internal" rows="4"
                            placeholder="Masukkan catatan internal" class="form-input form-textarea resize-none"></textarea>

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
                        :class="kredensialOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
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
                                    placeholder="Username pengguna untuk mengakses alat" class="form-input pr-12"
                                    readonly>
                            </div>
                            <p class="form-helper">Username pengguna untuk mengakses alat</p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label">Password</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                    x-model="formData.password" placeholder="Masukkan password"
                                    class="form-input pr-12 @error('password') error @enderror" required>
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <span class="relative w-5 h-5">
                                        <img x-show="!showPassword" src="{{ asset('assets/icons/eye_on.svg') }}"
                                            alt="Show Password" class="w-5 h-5 absolute inset-0">
                                        <img x-show="showPassword" src="{{ asset('assets/icons/eye_off.svg') }}"
                                            alt="Hide Password" class="w-5 h-5 absolute inset-0">
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
                                placeholder="b4c0f8a3-79f9-4a5f-8d8e-1a5b2c3d4e5f" class="form-input pr-12" readonly>
                            <button type="button" @click="copyApiKey()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <img src="{{ asset('assets/icons/copy.svg') }}" alt="Copy" class="w-5 h-5">
                            </button>
                        </div>
                        <p class="form-helper">API Key untuk diterapkan pada alat pengguna</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-start space-x-3 pt-4">
                <button type="submit"
                    class="btn-3d-green px-8 py-3 font-medium rounded-xl transition-colors duration-200">
                    Simpan Pengguna
                </button>
                <button type="button" @click="resetForm()"
                    class="px-8 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </button>
            </div>
        </form>

        <!-- Modal Pilih Pengguna -->
        <div x-show="showUserModal" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

            <!-- Overlay - Background sedikit gelap -->
            <div class="fixed inset-0 bg-black/30" @click="showUserModal = false"></div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl" @click.away="showUserModal = false">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pilih Pengguna</h3>
                        <button @click="showUserModal = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="relative">
                            <input type="text" x-model="searchUser" placeholder="Cari pengguna..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-color focus:border-transparent">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- User List -->
                    <div class="max-h-96 overflow-y-auto">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer"
                                @click="selectUser(user)">
                                <div class="flex items-center gap-3">
                                    <!-- Avatar -->
                                    <img :src="user.profileImage || '{{ asset('assets/images/default-avatar.jpg') }}'"
                                        :alt="user.nama" loading="lazy" class="w-10 h-10 rounded-full object-cover">

                                    <!-- User Info -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 truncate" x-text="user.nama"></h4>
                                        <p class="text-sm text-gray-500" x-text="user.whatsapp + ' · ' + user.email"></p>
                                    </div>

                                    <!-- Jenis Badge -->
                                    <span class="text-xs text-gray-400" x-text="user.jenis"></span>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <template x-if="filteredUsers.length === 0">
                            <div class="px-6 py-8 text-center">
                                <p class="text-gray-500">Pengguna tidak ditemukan</p>
                            </div>
                        </template>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-50 rounded-b-2xl">
                        <p class="text-sm text-gray-500 text-center">Pilih pengguna atau tutup untuk membuat baru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Script -->
    <script>
        function tambahPengguna() {
            return {
                dataWajibOpen: true,
                dataOpsionalOpen: false,
                kredensialOpen: false,
                showUserModal: false,
                searchUser: '',
                formData: {
                    nama_pengguna: '',
                    nomor_whatsapp: '',
                    email: '',
                    jenis_pengguna: '',
                    password: '',
                    nama_panggilan: '',
                    jenis_kelamin: '',
                    tanggal_lahir: '',
                    hp_lain: '',
                    alamat_lengkap: '',
                    pekerjaan: '',
                    wilayah: '',
                    catatan_internal: '',
                    username: '',
                    api_key: ''
                },
                showPassword: false,
                isEditing: true,
                dummyUsers: [{
                        id: 1,
                        nama: 'Budi Santoso',
                        email: 'budi@gmail.com',
                        whatsapp: '08123456789',
                        jenis: 'Individu',
                        profileImage: null
                    },
                    {
                        id: 2,
                        nama: 'Sari Dewi',
                        email: 'sari@gmail.com',
                        whatsapp: '08234567890',
                        jenis: 'Individu',
                        profileImage: null
                    },
                    {
                        id: 3,
                        nama: 'Ahmad Wijaya',
                        email: 'ahmad@gmail.com',
                        whatsapp: '08345678901',
                        jenis: 'Institusi/Lembaga',
                        profileImage: null
                    },
                    {
                        id: 4,
                        nama: 'Dewi Lestari',
                        email: 'dewi@gmail.com',
                        whatsapp: '08456789012',
                        jenis: 'Individu',
                        profileImage: null
                    },
                    {
                        id: 5,
                        nama: 'Eko Prasetyo',
                        email: 'eko@gmail.com',
                        whatsapp: '08567890123',
                        jenis: 'Individu',
                        profileImage: null
                    }
                ],

                get filteredUsers() {
                    if (!this.searchUser) return this.dummyUsers;
                    return this.dummyUsers.filter(user =>
                        user.nama.toLowerCase().includes(this.searchUser.toLowerCase()) ||
                        user.email.toLowerCase().includes(this.searchUser.toLowerCase()) ||
                        user.whatsapp.includes(this.searchUser)
                    );
                },

                selectUser(user) {
                    this.formData.nama_pengguna = user.nama;
                    this.formData.email = user.email;
                    this.formData.nomor_whatsapp = user.whatsapp;
                    this.formData.jenis_pengguna = user.jenis;
                    this.showUserModal = false;
                    this.searchUser = '';
                },

                resetForm() {
                    this.formData = {
                        nama_pengguna: '',
                        nomor_whatsapp: '',
                        email: '',
                        jenis_pengguna: '',
                        password: '',
                        nama_panggilan: '',
                        jenis_kelamin: '',
                        tanggal_lahir: '',
                        hp_lain: '',
                        alamat_lengkap: '',
                        pekerjaan: '',
                        wilayah: '',
                        catatan_internal: '',
                        username: '',
                        api_key: ''
                    };
                },

                copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        // Optional: show toast notification
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
