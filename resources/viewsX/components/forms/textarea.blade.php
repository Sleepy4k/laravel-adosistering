@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'helper' => null,
    'required' => false,
    'value' => null,
    'model' => null,
    'rows' => 4
])

@php
    $hasError = $errors->has($name);
    $inputClasses = 'form-input form-textarea resize-none' . ($hasError ? ' error' : '');
@endphp

<div class="w-full flex flex-col gap-2">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        class="{{ $inputClasses }}"
        @if($model) x-model="{{ $model }}" @endif
        @if($required) required @endif
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>

    @if($helper && !$hasError)
        <p class="form-helper">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
