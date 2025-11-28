@props([
    'title' => 'Login Akun',
    'action' => '#',
    'backgroundImage' => '',
    'buttonColor' => 'bg-blue-600',
    'footerLinkText' => 'Atau login sebagai',
    'footerLinkRole' => '',
    'footerLinkUrl' => '#'
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen w-full flex items-center justify-center bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $backgroundImage }}');">
    <!-- Card Container - Pixel Perfect Figma Specs -->
    <div class="w-[640px] h-auto p-12 flex flex-col gap-6 rounded-3xl border-2 border-white bg-[rgba(255,255,255,0.72)] backdrop-blur-md box-border">
        
        <!-- Title -->
        <h3 class="text-[44px] font-normal text-center text-gray-800 leading-tight w-full">
            {{ $title }}
        </h3>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif


        <!-- Login Form -->
        <form method="POST" action="{{ $action }}" class="w-full flex flex-col gap-6">
            @csrf


            <!-- Username Field -->
            <div class="w-full flex flex-col gap-2">
                <label for="username" class="text-sm font-medium text-gray-700">
                    Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    class="w-full h-[52px] rounded-xl px-4 bg-white text-gray-900 border transition-all duration-200 placeholder:text-gray-400 text-base outline-none {{ $errors->has('username') ? 'border-red-500' : '' }}"
                    placeholder="Masukkan username"
                />
                @error('username')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="w-full flex flex-col gap-2" x-data="{ showPassword: false }">
                <label for="password" class="text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="relative">
                    <input
                        x-bind:type="showPassword ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        class="w-full h-[52px] rounded-xl px-4 pr-12 bg-white text-gray-900 border transition-all duration-200 placeholder:text-gray-400 text-base outline-none {{ $errors->has('password') ? 'border-red-500' : '' }}"
                        placeholder="Masukkan password"
                    />
                    <button
                        type="button"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
                        @click="showPassword = !showPassword"
                        tabindex="-1"
                    >
                        <!-- Eye Icon (Show Password) -->
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        
                        <!-- Eye Slash Icon (Hide Password) -->
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full {{ $buttonColor }} text-white rounded-xl h-[52px] font-medium text-base hover:opacity-90 active:scale-[0.98] transition duration-200 shadow-sm box-border"
            >
                Login
            </button>
        </form>

        <!-- Footer Link -->
        @if($footerLinkRole && $footerLinkUrl !== '#')
            <div class="w-full text-center">
                <p class="text-sm text-gray-600">
                    {{ $footerLinkText }} <a href="{{ $footerLinkUrl }}" class="underline font-medium text-gray-700 hover:text-gray-900 transition">{{ $footerLinkRole }}</a>
                </p>
            </div>
        @endif
    </div>
</body>
</html>
