<a wire:click.prevent="columnAction('{{ $attribute }}', '{{ $modelKey }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class])
   href=""
   title="{{ $title }}">
    {!! $icon !!} {{ $title }}
</a>
