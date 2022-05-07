<select wire:change="filter('{{ $filter->identifier }}', $event.target.value)"
        @class(['form-select', $filter->class => $filter->class])
        {!! $filter->multiple ? 'multiple' : null !!}
        aria-label="{{ $filter->label }}">
    <option selected>{{ $filter->label }}</option>
    @foreach($filter->options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
    @endforeach
</select>
