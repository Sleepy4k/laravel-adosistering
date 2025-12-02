@props([
    'name',
    'label' => null,
    'placeholder' => 'Pilih opsi',
    'helper' => null,
    'required' => false,
    'options' => [],
    'model' => null
])

@php
    $hasError = $errors->has($name);
    $inputClasses = 'form-input form-select text-left flex items-center justify-between cursor-pointer' . ($hasError ? ' error' : '');
@endphp

<div class="w-full flex flex-col gap-2" x-data="{ open: false }">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
        </label>
    @endif

    <input type="hidden" name="{{ $name }}" @if($model) x-model="{{ $model }}" @endif>
    
    <div class="relative">
        <button @click="open = !open"
                type="button"
                class="{{ $inputClasses }}">
            <span @if($model) x-text="{{ $model }} || '{{ $placeholder }}'" :class="{{ $model }} ? 'text-gray-900' : 'text-gray-400'" @else class="text-gray-400" @endif>
                {{ $placeholder }}
            </span>
            <svg class="w-4 h-4 text-gray-400 transition-transform" 
                 :class="open ? 'rotate-180' : ''" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div x-show="open" 
             x-transition
             @click.away="open = false"
             class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg"
             style="display: none;">
            @foreach($options as $optionValue => $optionLabel)
                <button @click="{{ $model }} = '{{ $optionLabel }}'; open = false" 
                        type="button"
                        class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 first:rounded-t-xl last:rounded-b-xl">
                    {{ $optionLabel }}
                </button>
            @endforeach
        </div>
    </div>

    @if($helper && !$hasError)
        <p class="form-helper">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
