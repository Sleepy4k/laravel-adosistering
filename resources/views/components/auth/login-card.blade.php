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
        /* Form Input Base Style */
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

        /* Focus State - Primary/100 #186D3C */
        .form-input:focus {
            border-color: #186D3C;
            outline: none;
        }

        /* Error State - Error/80 #E47689 */
        .form-input.error {
            border-color: #E47689;
        }

        /* Error + Focus State */
        .form-input.error:focus {
            border-color: #E47689;
            outline: none;
        }

        /* Form Label Styles */
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        /* Form Error Text */
        .form-error {
            font-size: 12px;
            color: #E47689;
            margin-top: 4px;
        }
    </style>
    
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
                <label for="username" class="form-label">
                    Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    class="form-input {{ $errors->has('username') ? 'error' : '' }}"
                    placeholder="Masukkan username"
                />
                @error('username')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="w-full flex flex-col gap-2" x-data="{ showPassword: false }">
                <label for="password" class="form-label">
                    Password
                </label>
                <div class="relative">
                    <input
                        x-bind:type="showPassword ? 'text' : 'password'"
                        id="password"
                        name="password"
                        required
                        class="form-input pr-12 {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="Masukkan password"
                    />
                    <button
                        type="button"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none w-5 h-5"
                        @click="showPassword = !showPassword"
                        tabindex="-1"
                    >
                        <!-- Eye Icon (Show Password) -->
                        <img x-show="!showPassword" src="{{ asset('assets/icons/eye_on.svg') }}" alt="Show Password" class="w-5 h-5 absolute inset-0">
                        
                        <!-- Eye Slash Icon (Hide Password) -->
                        <img x-show="showPassword" src="{{ asset('assets/icons/eye_off.svg') }}" alt="Hide Password" class="w-5 h-5 absolute inset-0">
                    </button>
                </div>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
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
