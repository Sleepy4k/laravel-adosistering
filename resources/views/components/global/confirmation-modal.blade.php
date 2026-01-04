@props([
    'title' => 'Anda Sudah Yakin ?',
    'message' => 'Tindakan ini tidak dapat dibatalkan.',
    'confirmText' => 'Saya Yakin',
    'cancelText' => 'Batal',
    'type' => 'warning' // warning, danger, info
])

{{--
    Confirmation Modal Component
    
    Usage:
    <x-global.confirmation-modal 
        x-show="showConfirm"
        title="Anda Sudah Yakin ?"
        message="Kata sandi akan dirubah dan akan digunakan ketika anda ingin masuk ke sistem"
        confirmText="Saya Yakin"
        cancelText="Batal"
        type="warning"
        @confirm="handleConfirm()"
        @cancel="showConfirm = false"
    />
--}}

@php
    $iconBgColor = match($type) {
        'danger' => 'bg-red-100',
        'info' => 'bg-blue-100',
        default => 'bg-amber-100',
    };
    
    $iconColor = match($type) {
        'danger' => 'text-red-500',
        'info' => 'text-blue-500',
        default => 'text-amber-500',
    };
@endphp

<div 
    {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center bg-black/50', 'style' => 'display: none;']) }}
    x-cloak
    @click.self="$dispatch('cancel')"
>
    <div 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-8 relative text-center"
    >
        <!-- Close Button -->
        <button 
            @click="$dispatch('cancel')"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Warning Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-full {{ $iconBgColor }} flex items-center justify-center">
                @if($type === 'danger')
                    <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                @elseif($type === 'info')
                    <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @else
                    <svg class="w-8 h-8 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                @endif
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-xl font-bold text-[#4F4F4F] mb-2">{{ $title }}</h3>
        
        <!-- Message -->
        <p class="text-sm text-gray-500 mb-6">{{ $message }}</p>

        <!-- Buttons -->
        <div class="grid grid-cols-2 gap-3">
            <button 
                @click="$dispatch('confirm')"
                class="btn-3d-green w-full h-12 text-sm"
            >
                {{ $confirmText }}
            </button>
            <button 
                @click="$dispatch('cancel')"
                class="w-full h-12 bg-white border border-[#C2C2C2] text-[#4F4F4F] rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors"
            >
                {{ $cancelText }}
            </button>
        </div>
    </div>
</div>
