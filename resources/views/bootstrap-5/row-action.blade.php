<a wire:click.prevent="rowAction('{{ $key }}', {{ $model->getKey() }}, {{ $shouldBeConfirmed ? 1 : 0 }})"
   @class(['btn btn-link', $class, 'p-0 mx-2'])
   href=""
   title="{{ $title }}">
    {!! $icon !!}
</a>
