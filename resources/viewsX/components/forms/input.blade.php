@props([
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'helper' => null,
    'required' => false,
    'value' => null,
    'model' => null,
    'autofocus' => false
])

@php
    $hasError = $errors->has($name);
    $inputClasses = 'form-input' . ($hasError ? ' error' : '');
@endphp

<div class="w-full flex flex-col gap-2">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="{{ $inputClasses }}"
        @if($model) x-model="{{ $model }}" @endif
        @if($required) required @endif
        @if($autofocus) autofocus @endif
        {{ $attributes }}
    />

    @if($helper && !$hasError)
        <p class="form-helper">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
