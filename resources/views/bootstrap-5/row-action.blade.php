<a wire:click.prevent="rowAction('{{ $identifier }}', {{ $modelKey }}, {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class, 'p-1'])
   href=""
   title="{{ $title }}">
    {!! $icon !!}
</a>
