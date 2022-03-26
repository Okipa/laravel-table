<a wire:click.prevent="cellAction('{{ $modelKey }}', '{{ $attribute }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class])
   href=""
   title="{{ $title }}">
    {!! $icon !!} {{ $title }}
</a>
