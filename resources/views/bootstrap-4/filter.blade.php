<div wire:key="filter-{{ Str::slug($filter->identifier) }}" class="ml-3">
    <select wire:model="selectedFilters.{{ $filter->identifier }}" {{ $attributes->class(['form-select', ...$class]) }}>
        <option wire:key="filter-option-{{ Str::slug($filter->identifier) }}-placeholder" value="" selected{!! $multiple ? ' disabled' : null !!}>{{ $label }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option wire:key="filter-option-{{ Str::slug($filter->identifier) }}-{{ Str::slug($optionValue) }}" value="{{ $optionValue }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
