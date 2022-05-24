<div wire:key="filter-{{ Str::slug($filter->identifier) }}" class="ms-3">
    <select wire:model="selectedFilters.{{ $filter->identifier }}"
            @class(['form-select', $class => $class])
            {!! $multiple ? 'multiple' : null !!}
            aria-label="{{ $label }}">
        <option wire:key="filter-option-{{ Str::slug($filter->identifier) }}-placeholder" value="" selected{!! $multiple ? ' hidden' : null !!}>{{ $label }}</option>
        @foreach($options as $optionValue => $optionLabel)
            <option wire:key="filter-option-{{ Str::slug($filter->identifier) }}-{{ Str::slug($optionValue) }}" value="{{ $optionValue }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
