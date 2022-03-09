<a wire:click.prevent="rowAction('{{ $key }}', {{ $modelKey }}, {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class, 'mx-2'])
   href=""
   title="{{ $title }}">
    {!! $icon !!}
</a>
