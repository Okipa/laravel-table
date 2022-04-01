<a wire:click.prevent="columnAction('{{ $attribute }}', '{{ $modelKey }}', {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class([$class])
   href=""
   title="{{ $title }}"
   data-bs-toggle="tooltip">
    {!! $icon !!}{{ $label ? ' ' . $label : null }}
</a>
