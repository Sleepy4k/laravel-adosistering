@props([
    'show' => 'showOtpModal',
    'email' => 'user@example.com',
    'onVerify' => '',
    'onResend' => '',
])

{{-- 
OTP Modal Component
- Modal untuk verifikasi kode OTP
- Menampilkan email tujuan pengiriman OTP
- Input 4 digit OTP
- Link kirim ulang OTP
- Button 3D untuk submit
--}}
<div 
    x-show="{{ $show }}" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    @click.self="{{ $show }} = false"
    style="display: none;"
>
    <div 
        x-show="{{ $show }}"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-8 relative"
        x-data="{
            otp: '',
            isResending: false,
            resendCooldown: 0,
            resendTimer: null,
            
            startResendCooldown() {
                this.resendCooldown = 60;
                this.resendTimer = setInterval(() => {
                    this.resendCooldown--;
                    if (this.resendCooldown <= 0) {
                        clearInterval(this.resendTimer);
                    }
                }, 1000);
            },
            
            resendOtp() {
                if (this.resendCooldown > 0) return;
                this.isResending = true;
                
                // Simulate resend (frontend only)
                setTimeout(() => {
                    this.isResending = false;
                    this.startResendCooldown();
                    {{ $onResend }}
                }, 1000);
            },
            
            verifyOtp() {
                if (this.otp.length === 4) {
                    {{ $onVerify }}
                }
            }
        }"
    >
        <!-- Close Button -->
        <button 
            @click="{{ $show }} = false"
            class="absolute top-6 right-6 text-gray-400 hover:text-gray-600"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Header -->
        <h3 class="text-lg font-semibold text-[#4F4F4F] mb-6">Ubah Kata Sandi</h3>
        
        <!-- Title -->
        <h2 class="text-xl font-bold text-[#4F4F4F] text-center mb-2">Masukkan Kode OTP</h2>
        
        <!-- Description -->
        <p class="text-sm text-gray-500 text-center mb-1">Masukkan kode OTP yang telah dikirim ke email :</p>
        <p class="text-sm font-semibold text-[#4F4F4F] text-center mb-8">{{ $email }}</p>

        <!-- OTP Input -->
        <div class="mb-6">
            <x-forms.otp-input :length="4" model="otp" />
        </div>

        <!-- Resend OTP -->
        <div class="text-center mb-8">
            <p class="text-sm text-gray-500 mb-1">Belum menerima OTP?</p>
            <button 
                type="button"
                @click="resendOtp()"
                :disabled="resendCooldown > 0 || isResending"
                class="text-sm text-primary hover:text-primary/80 font-medium disabled:text-gray-400 disabled:cursor-not-allowed"
            >
                <span x-show="!isResending && resendCooldown <= 0">Kirim Ulang OTP</span>
                <span x-show="isResending">Mengirim...</span>
                <span x-show="resendCooldown > 0">Kirim ulang dalam <span x-text="resendCooldown"></span>s</span>
            </button>
        </div>

        <!-- Submit Button -->
        <button 
            type="button"
            @click="verifyOtp()"
            :disabled="otp.length !== 4"
            class="btn-3d-green w-full h-12 text-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
        >
            Berikutnya
        </button>
    </div>
</div>
