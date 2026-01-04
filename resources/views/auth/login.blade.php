   <!DOCTYPE html>
   <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <meta name="csrf-token" content="{{ csrf_token() }}">
       <title>Login - {{ config('app.name', 'Laravel') }}</title>

       <!-- Tailwind CSS CDN -->
       <script src="https://cdn.tailwindcss.com"></script>
       <script>
           tailwind.config = {
               theme: {
                   extend: {
                       colors: {
                           'primary-color': '#4F46E5',
                           'primary-hover': '#4338CA',
                           'primary-darker': '#3730A3'
                       }
                   }
               }
           }
       </script>

       <!-- Custom Form Styles -->
       <style>
           [x-cloak] {
               display: none !important;
           }

           .form-input {
               width: 100%;
               height: 49px;
               border-radius: 16px;
               padding: 0 16px;
               background-color: white;
               color: #111827;
               border: 1px solid #D1D5DB;
               transition: all 0.2s ease;
               font-size: 16px;
               outline: none;
           }

           .form-input::placeholder {
               color: #9CA3AF;
           }

           .form-input:focus {
               border-color: #186D3C;
               outline: none;
           }

           .form-input.error {
               border-color: #E47689;
           }

           .form-input.error:focus {
               border-color: #E47689;
               outline: none;
           }

           .form-label {
               display: block;
               font-size: 14px;
               font-weight: 500;
               color: #374151;
               margin-bottom: 6px;
           }

           .form-error {
               font-size: 12px;
               color: #E47689;
               margin-top: 4px;
           }
       </style>
   </head>

   <body class="min-h-screen w-full flex items-center justify-center bg-cover bg-center bg-no-repeat"
       style="background-image: url('{{ asset('assets/images/auth/bg-login-user.jpg') }}');">

       <!-- Card Container -->
       <div class="w-[640px] h-auto p-12 flex flex-col gap-6 rounded-3xl border-2 border-white bg-[rgba(255,255,255,0.72)] backdrop-blur-md box-border"
           x-data="loginForm()">

           <!-- Title -->
           <h3 class="text-[44px] font-normal text-center text-gray-800 leading-tight w-full">
               Login Akun
           </h3>

           <!-- Flash Messages -->
           @if (session('success'))
               <div class="w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                   {{ session('success') }}
               </div>
           @endif

           @if (session('error'))
               <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm">
                   {{ session('error') }}
               </div>
           @endif

           <!-- Blocked Warning (Only show when blocked) -->
           <template x-if="isBlocked && remainingSeconds > 0">
               <div class="w-full bg-red-50 border-2 border-red-400 text-red-800 px-5 py-4 rounded-lg">
                   <div class="flex items-center gap-3">
                       <svg class="w-6 h-6 text-red-600 shrink-0" fill="none" stroke="currentColor"
                           viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                               d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                           </path>
                       </svg>
                       <div class="flex">
                           <p class="text-sm">Terlalu banyak percobaan login yang gagal Coba lagi dalam: <span
                                   x-text="remainingSeconds" class="text-sm"></span>
                               detik</p>
                       </div>
                   </div>
               </div>
           </template>

           <!-- Login Form -->
           <form method="POST" action="{{ route('login.submit') }}" class="w-full flex flex-col gap-6"
               :class="isBlocked && remainingSeconds > 0 ? 'opacity-50 pointer-events-none' : ''">
               @csrf

               <!-- Email Field -->
               <div class="w-full flex flex-col gap-2">
                   <label for="email" class="form-label">Email</label>
                   <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="Masukkan email" />
                   @error('email')
                       <span class="form-error">{{ $message }}</span>
                   @enderror
               </div>

               <!-- Password Field -->
               <div class="w-full flex flex-col gap-2">
                   <label for="password" class="form-label">Password</label>
                   <div class="relative">
                       <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                           class="form-input pr-12 {{ $errors->has('password') ? 'error' : '' }}"
                           placeholder="Masukkan password" />
                       <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                           class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                           <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                               viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                   d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                   d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                               </path>
                           </svg>
                           <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                               viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                   d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                               </path>
                           </svg>
                       </button>
                   </div>
                   @error('password')
                       <span class="form-error">{{ $message }}</span>
                   @enderror
               </div>

               <!-- Submit Button -->
               <button type="submit" :disabled="isBlocked && remainingSeconds > 0"
                   class="w-full bg-gray-800 text-white rounded-xl h-[52px] font-medium text-base hover:opacity-90 active:scale-[0.98] transition duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                   <span x-show="!isBlocked || remainingSeconds <= 0">Login</span>
                   <span x-show="isBlocked && remainingSeconds > 0" x-cloak
                       class="flex items-center justify-center gap-2">
                       <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                           <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                               stroke-width="4"></circle>
                           <path class="opacity-75" fill="currentColor"
                               d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                           </path>
                       </svg>
                       Tunggu <span x-text="remainingSeconds" class="font-mono font-bold"></span> detik
                   </span>
               </button>
           </form>
       </div>

       <!-- Login Form Component - HARUS sebelum Alpine.js -->
       <script>
           // Data dari PHP
           const BLOCKED_UNTIL = {{ session('blocked_until', 0) }};
           const IS_BLOCKED = {{ $isBlocked ?? false ? 'true' : 'false' }};

           document.addEventListener('alpine:init', () => {
               Alpine.data('loginForm', () => ({
                   showPassword: false,
                   isBlocked: IS_BLOCKED,
                   remainingSeconds: 0,
                   intervalId: null,

                   init() {
                       if (BLOCKED_UNTIL > 0) {
                           const now = Math.floor(Date.now() / 1000);
                           this.remainingSeconds = Math.max(0, BLOCKED_UNTIL - now);

                           if (this.remainingSeconds > 0) {
                               this.isBlocked = true;
                               this.startCountdown();
                           }
                       }
                   },

                   startCountdown() {
                       // Clear existing interval
                       if (this.intervalId) {
                           clearInterval(this.intervalId);
                       }

                       // Update setiap detik
                       this.intervalId = setInterval(() => {
                           const now = Math.floor(Date.now() / 1000);
                           this.remainingSeconds = Math.max(0, BLOCKED_UNTIL - now);

                           // Countdown selesai
                           if (this.remainingSeconds <= 0) {
                               clearInterval(this.intervalId);
                               this.isBlocked = false;
                               window.location.reload();
                           }
                       }, 1000);
                   }
               }));
           });
       </script>

       <!-- Alpine.js - HARUS setelah script di atas -->
       <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
   </body>

   </html>
