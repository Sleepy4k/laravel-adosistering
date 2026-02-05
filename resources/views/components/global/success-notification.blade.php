@props([
    'title' => 'Berhasil',
    'message' => 'Aksi berhasil dilakukan.',
    'buttonText' => 'Tutup'
])

{{--
    Success Notification Component
    
    Usage:
    <x-global.success-notification 
        x-show="showSuccess"
        :title="successTitle"
        :message="successMessage"
        buttonText="Tutup"
        @close="showSuccess = false"
    />
--}}

<div 
    {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center bg-black/50']) }}
    x-cloak
    @click.self="$dispatch('close')"
>
    <div 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-8 relative text-center"
        @click.stop
    >
        <!-- Close Button -->
        <button 
            @click="$dispatch('close')"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Success Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-xl font-bold text-[#4F4F4F] mb-2" x-text="successTitle"></h3>
        
        <!-- Message -->
        <p class="text-sm text-gray-500 mb-6 whitespace-pre-line" x-text="successMessage"></p>

        <!-- Button -->
        <button 
            @click="$dispatch('close')"
            class="btn-3d-green w-full h-12 text-sm"
        >
            {{ $buttonText }}
        </button>
    </div>
</div>
