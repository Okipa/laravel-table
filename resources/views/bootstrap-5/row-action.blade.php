<a wire:key="row-action-{{ Str::slug($rowAction->identifier) }}-{{ $rowAction->modelKey }}"
   wire:click.prevent="rowAction('{{ $rowAction->identifier }}', '{{ $rowAction->modelKey }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([...$class, 'p-1'])
   href=""
   title="{{ $title }}"
   data-bs-toggle="tooltip">
    {!! $icon !!}
</a>
