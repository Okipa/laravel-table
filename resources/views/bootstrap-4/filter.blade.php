<div wire:key="filter-{{ Str::of($filter->identifier)->snake('-')->slug() }}" class="ml-3">
    <select wire:model="selectedFilters.{{ $filter->identifier }}" {{ $attributes->class(['form-select', ...$class]) }}>
        <option wire:key="filter-option-{{ Str::of($filter->identifier)->snake('-')->slug() }}-placeholder" value="" selected{!! $multiple ? ' disabled' : null !!}>{{ $label }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option wire:key="filter-option-{{ Str::of($filter->identifier)->snake('-')->slug() }}-{{ Str::of($optionValue)->snake('-')->slug() }}" value="{{ $optionValue }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
