<div class="mx-2">
    <a wire:click.prevent="rowAction('{{ $key }}', {{ $model->getKey() }}, {{ $shouldBeConfirmed ? 1 : 0 }})"
       class="btn btn-link p-0"
       href=""
       title="{{ $title }}">
        {!! $icon !!}
    </a>
</div>
