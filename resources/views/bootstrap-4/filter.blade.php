<div wire:key="{{ Str::of($filter->identifier)->snake('-')->slug() }}" class="ml-3 mt-2">
    <select wire:model="selectedFilters.{{ $filter->identifier }}" {{ $attributes->class(['custom-select', ...$class]) }}>
        <option wire:key="{{ Str::of($filter->identifier)->snake('-')->slug() }}-option-placeholder" value="" selected{!! $multiple ? ' disabled' : null !!}>{{ $label }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option wire:key="{{ Str::of($filter->identifier)->snake('-')->slug() }}-option-{{ Str::of($optionValue)->snake('-')->slug() }}" value="{{ $optionValue }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
