<a wire:click.prevent="rowAction('{{ $key }}', {{ $modelKey }}, {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class, 'p-2'])
   href=""
   title="{{ $title }}">
    {!! $icon !!}
</a>
