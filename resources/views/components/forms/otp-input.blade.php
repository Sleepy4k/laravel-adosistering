@props([
    'length' => 4,
    'model' => 'otp',
])

{{-- 
OTP Input Component
- 4 digit input dengan auto-focus ke input berikutnya
- Styling: border hijau, rounded-xl, text center
--}}
<div 
    x-data="{
        digits: Array({{ $length }}).fill(''),
        focusNext(index) {
            if (index < {{ $length - 1 }}) {
                this.$refs['digit' + (index + 1)].focus();
            }
        },
        focusPrev(index) {
            if (index > 0) {
                this.$refs['digit' + (index - 1)].focus();
            }
        },
        handleInput(index, event) {
            const value = event.target.value;
            
            // Only allow single digit
            if (value.length > 1) {
                this.digits[index] = value.slice(-1);
            }
            
            // Auto focus to next input
            if (value && index < {{ $length - 1 }}) {
                this.focusNext(index);
            }
            
            // Update parent model
            this.updateModel();
        },
        handleKeydown(index, event) {
            // Backspace: clear current and focus previous
            if (event.key === 'Backspace' && !this.digits[index] && index > 0) {
                this.focusPrev(index);
            }
            
            // Arrow keys navigation
            if (event.key === 'ArrowLeft' && index > 0) {
                this.focusPrev(index);
            }
            if (event.key === 'ArrowRight' && index < {{ $length - 1 }}) {
                this.focusNext(index);
            }
        },
        handlePaste(event) {
            event.preventDefault();
            const paste = (event.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/\D/g, '').slice(0, {{ $length }}).split('');
            
            digits.forEach((digit, index) => {
                if (index < {{ $length }}) {
                    this.digits[index] = digit;
                }
            });
            
            // Focus last filled input or next empty
            const lastIndex = Math.min(digits.length, {{ $length - 1 }});
            this.$refs['digit' + lastIndex]?.focus();
            
            this.updateModel();
        },
        updateModel() {
            {{ $model }} = this.digits.join('');
        }
    }"
    class="flex justify-center gap-3"
    @paste="handlePaste($event)"
>
    @for ($i = 0; $i < $length; $i++)
        <input 
            type="text"
            inputmode="numeric"
            maxlength="1"
            x-ref="digit{{ $i }}"
            x-model="digits[{{ $i }}]"
            @input="handleInput({{ $i }}, $event)"
            @keydown="handleKeydown({{ $i }}, $event)"
            class="w-14 h-14 text-center text-xl font-semibold text-[#4F4F4F] bg-white border-2 border-primary rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/30 placeholder:text-gray-300"
            placeholder="-"
        />
    @endfor
</div>
