@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="manajemenUser()">

        <!-- Flash Messages from Backend -->
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

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6" role="alert">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Alpine.js Flash Messages -->
        <div x-show="notification.show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="rounded-xl mb-6 px-4 py-3" 
            :class="notification.type === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700'"
            role="alert"
            style="display: none;">
            <div class="flex items-center">
                <div class="shrink-0">
                    <svg x-show="notification.type === 'success'" class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <svg x-show="notification.type === 'error'" class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium" x-text="notification.message"></p>
                </div>
            </div>
        </div>

        <!-- Header Section with Title -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Manajemen User</h1>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div>
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('admin.dashboard') }}" class="text-base hover:text-primary-color transition-colors">Beranda</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-base text-gray-400">Manajemen User</span>
            </nav>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Left side: Filters -->
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <!-- Role Filter -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-[#4F4F4F]">Role</label>
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                type="button"
                                @click="open = !open"
                                class="w-[200px] h-11 px-4 bg-white border border-gray-300 rounded-xl flex items-center justify-between text-sm text-gray-700 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            >
                                <span x-text="roleFilter === '' ? 'Semua' : (roleFilter === 'petani' ? 'Petani' : 'Admin')"></span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div 
                                x-show="open" 
                                x-transition 
                                @click.away="open = false"
                                class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg"
                                style="display: none;"
                            >
                                <button type="button" @click="roleFilter = ''; filterUsers(); open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-xl">Semua</button>
                                <button type="button" @click="roleFilter = 'petani'; filterUsers(); open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Petani</button>
                                <button type="button" @click="roleFilter = 'admin'; filterUsers(); open = false" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 last:rounded-b-xl">Admin</button>
                            </div>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="flex flex-col gap-2 flex-1">
                        <label class="text-sm font-medium text-[#4F4F4F]">Cari Pengguna</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                placeholder="Cari nama, email, atau nomor WA..."
                                x-model="searchQuery"
                                @input="filterUsers()"
                                class="w-full h-11 pl-10 pr-4 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            >
                        </div>
                    </div>
                </div>

                <!-- Right side: Add User Button -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-transparent hidden lg:block">Action</label>
                    <a href="{{ route('admin.users.create') }}" 
                       class="btn-3d-green inline-flex items-center gap-2 whitespace-nowrap h-11">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Tambah Pengguna</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- User List Table -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] overflow-hidden">
            <!-- Table Header -->
            <div class="hidden md:grid md:grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-[#E5E5E5] text-sm font-semibold text-[#4F4F4F]">
                <div class="col-span-1 text-center">No</div>
                <div class="col-span-4">Nama</div>
                <div class="col-span-3">Email</div>
                <div class="col-span-2">No. WhatsApp</div>
                <div class="col-span-2 text-center">Aksi</div>
            </div>

            <!-- Table Body -->
            <template x-for="(user, index) in filteredUsers" :key="user.id">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 px-6 py-4 border-b border-[#E5E5E5] hover:bg-gray-50 transition-colors items-center">
                    <!-- Number -->
                    <div class="hidden md:flex col-span-1 text-sm text-[#4F4F4F] justify-center" x-text="index + 1"></div>

                    <!-- Name with Profile Image -->
                    <div class="col-span-1 md:col-span-4">
                        <div class="flex items-center gap-3">
                            <img :src="user.profileImage || '{{ asset('assets/images/default-avatar.jpg') }}'" 
                                 :alt="user.name"
                                 class="w-10 h-10 rounded-full object-cover shrink-0">
                            <div>
                                <p class="font-medium text-[#4F4F4F]" x-text="user.name"></p>
                                <p class="text-xs text-gray-400 md:hidden" x-text="user.email"></p>
                                <span class="md:hidden px-2 py-0.5 text-xs font-medium rounded-full mt-1 inline-block"
                                    :class="user.role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'"
                                    x-text="user.role.charAt(0).toUpperCase() + user.role.slice(1)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="hidden md:flex col-span-3 text-sm text-[#4F4F4F] items-center truncate" x-text="user.email"></div>

                    <!-- WhatsApp -->
                    <div class="hidden md:flex col-span-2 text-sm text-[#4F4F4F] items-center" x-text="user.whatsapp"></div>

                    <!-- Mobile: Additional Info Row -->
                    <div class="md:hidden col-span-1 flex flex-wrap gap-2 text-xs text-gray-500">
                        <span x-text="user.whatsapp"></span>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-1 md:col-span-2 flex items-center justify-start md:justify-center gap-2">
                        <!-- Edit Button -->
                        <a :href="'/admin/users/' + user.id + '/edit'" 
                            class="btn-3d-blue inline-flex items-center gap-2 text-sm px-4! py-2!">
                            <img src="{{ asset('assets/icons/edit.svg') }}" alt="Edit" class="w-4 h-4">
                            <span>Edit</span>
                        </a>
                        
                        <!-- Delete Button -->
                        <button type="button" 
                            @click="confirmDelete(user)"
                            class="btn-3d-red inline-flex items-center gap-2 text-sm px-4! py-2!">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredUsers.length === 0" class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada user ditemukan</h3>
                <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian atau tambahkan user baru.</p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-500">
                Menampilkan <span class="font-medium" x-text="filteredUsers.length"></span> dari <span class="font-medium" x-text="users.length"></span> user
            </div>
            <div class="flex items-center gap-2">
                <button type="button" 
                    :disabled="currentPage === 1"
                    @click="currentPage--"
                    class="px-4 py-2 border border-[#C2C2C2] rounded-lg text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Sebelumnya
                </button>
                <span class="px-4 py-2 text-sm text-[#4F4F4F]">
                    Halaman <span x-text="currentPage"></span>
                </span>
                <button type="button" 
                    :disabled="currentPage >= totalPages"
                    @click="currentPage++"
                    class="px-4 py-2 border border-[#C2C2C2] rounded-lg text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Selanjutnya
                </button>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showDeleteModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/50 transition-opacity"
                    @click="showDeleteModal = false"></div>

                <!-- Hidden element for centering -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showDeleteModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative inline-block align-bottom bg-white rounded-2xl px-6 pt-5 pb-6 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#4F4F4F] mb-2">Hapus User</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Apakah Anda yakin ingin menghapus user <strong x-text="userToDelete?.name"></strong>? 
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-3">
                        <button type="button" 
                            @click="showDeleteModal = false"
                            class="px-6 py-3 border border-[#C2C2C2] rounded-xl text-sm font-medium text-[#4F4F4F] hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="button" @click="deleteUser()" class="btn-3d-red inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Ya, Hapus</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function manajemenUser() {
            return {
                searchQuery: '',
                roleFilter: '',
                currentPage: 1,
                perPage: 10,
                showDeleteModal: false,
                userToDelete: null,
                deleteUrl: '',
                notification: {
                    show: false,
                    type: 'success',
                    message: ''
                },
                
                // Data dari backend melalui Blade
                users: @json($users ?? []),

                get filteredUsers() {
                    let result = this.users;

                    // Search filter
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        result = result.filter(user => 
                            user.name.toLowerCase().includes(query) ||
                            user.email.toLowerCase().includes(query) ||
                            user.whatsapp.includes(query)
                        );
                    }

                    // Role filter
                    if (this.roleFilter) {
                        result = result.filter(user => user.role === this.roleFilter);
                    }

                    return result;
                },

                get totalPages() {
                    return Math.ceil(this.filteredUsers.length / this.perPage);
                },

                filterUsers() {
                    this.currentPage = 1;
                },

                showNotification(type, message) {
                    this.notification.type = type;
                    this.notification.message = message;
                    this.notification.show = true;
                    
                    // Auto hide after 5 seconds
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 5000);
                },

                confirmDelete(user) {
                    this.userToDelete = user;
                    this.deleteUrl = '/admin/users/' + user.id;
                    this.showDeleteModal = true;
                },

                deleteUser() {
                    if (this.userToDelete) {
                        const userName = this.userToDelete.name;
                        // Remove user from array (dummy action)
                        this.users = this.users.filter(u => u.id !== this.userToDelete.id);
                        this.showDeleteModal = false;
                        this.userToDelete = null;
                        
                        // Show notification
                        this.showNotification('success', `Pengguna ${userName} berhasil dihapus!`);
                    }
                }
            }
        }
    </script>
@endpush
