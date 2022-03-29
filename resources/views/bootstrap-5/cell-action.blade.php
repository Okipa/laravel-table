<a wire:click.prevent="columnAction('{{ $modelKey }}', '{{ $attribute }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class])
   href=""
   title="{{ $title }}">
    {!! $icon !!} {{ $title }}
</a>
