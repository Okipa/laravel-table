<a wire:key="column-action-{{ Str::of($columnAction->attribute)->snake('-')->slug() }}-{{ $columnAction->modelKey }}"
   wire:click.prevent="columnAction('{{ $columnAction->attribute }}', '{{ $columnAction->modelKey }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([...$class, 'p-1'])
   href=""
   title="{{ $title }}"
   data-toggle="tooltip">
    {!! $icon !!}{{ $label ? ' ' . $label : null }}
</a>
