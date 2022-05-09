<a wire:key="row-action-{{ Str::slug($identifier) }}-{{ $modelKey }}"
   wire:click.prevent="rowAction('{{ $identifier }}', '{{ $modelKey }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class, 'p-1'])
   href=""
   title="{{ $title }}"
   data-bs-toggle="tooltip">
    {!! $icon !!}
</a>
