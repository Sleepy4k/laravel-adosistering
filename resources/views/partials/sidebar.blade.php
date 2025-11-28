<aside class="w-64 min-h-screen bg-white border-r border-gray-200 shadow-sm flex flex-col">
    <!-- Header: Logo and Toggle Button -->
    <div class="p-6 flex items-center justify-between border-b border-gray-200">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/icons/logo.svg') }}" alt="Logo" class="w-8 h-8">
            <span class="text-lg font-semibold text-gray-800">Dashboard</span>
        </div>
        <button class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
            <img src="{{ asset('images/icons/chevron-left.svg') }}" alt="Toggle" class="w-4 h-4">
        </button>
    </div>

    <!-- Main Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1">
        <!-- Section Label -->
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
            Main Nav
        </p>

        <!-- Beranda (Active State) -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium bg-green-500 text-white">
            <img src="{{ asset('images/icons/home.svg') }}" alt="Beranda" class="w-5 h-5 brightness-0 invert">
            <span>Beranda</span>
        </a>

        <!-- Riwayat Irigasi -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <img src="{{ asset('images/icons/history.svg') }}" alt="Riwayat Irigasi" class="w-5 h-5">
            <span>Riwayat Irigasi</span>
        </a>

        <!-- Profil -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <img src="{{ asset('images/icons/profile.svg') }}" alt="Profil" class="w-5 h-5">
            <span>Profil</span>
        </a>

        <!-- Statistik -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <img src="{{ asset('images/icons/statistic.svg') }}" alt="Statistik" class="w-5 h-5">
            <span>Statistik</span>
        </a>

        <!-- Notifikasi -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <img src="{{ asset('images/icons/notification.svg') }}" alt="Notifikasi" class="w-5 h-5">
            <span>Notifikasi</span>
        </a>

        <!-- Pengaturan -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <img src="{{ asset('images/icons/settings.svg') }}" alt="Pengaturan" class="w-5 h-5">
            <span>Pengaturan</span>
        </a>
    </nav>

    <!-- Bottom Navigation -->
    <div class="px-3 py-4 border-t border-gray-200 space-y-1">
        <!-- Pusat Bantuan -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
            <img src="{{ asset('images/icons/help.svg') }}" alt="Pusat Bantuan" class="w-5 h-5">
            <span>Pusat Bantuan</span>
        </a>

        <!-- Log Out -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
            <img src="{{ asset('images/icons/logout.svg') }}" alt="Log Out" class="w-5 h-5">
            <span>Log Out</span>
        </a>
    </div>
</aside>
