<a wire:click.prevent="columnAction('{{ $columnAction->attribute }}', '{{ $columnAction->modelKey }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
    @class([...$class, 'p-1'])
    href=""
    title="{{ $title }}"
    data-bs-toggle="tooltip"
>
    {!! $icon !!}{{ $label ? ' ' . $label : null }}
</a>
