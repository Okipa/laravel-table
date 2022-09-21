<a wire:key="{{ Str::of($rowAction->identifier)->snake('-')->slug() }}-{{ $rowAction->modelKey }}"
   wire:click.prevent="rowAction('{{ $rowAction->identifier }}', '{{ $rowAction->modelKey }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([...$class, 'p-1'])
   href=""
   title="{{ $title }}"
   data-toggle="tooltip">
    {!! $icon !!}
</a>
