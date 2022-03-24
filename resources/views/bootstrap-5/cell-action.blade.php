<a wire:click.prevent="cellAction('{{ $identifier }}', {{ $modelKey }}, {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class, 'p-1'])
   href=""
   title="{{ $title }}">
    {!! $icon !!} {{ $title }}
</a>
