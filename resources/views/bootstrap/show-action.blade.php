@if($table->isRouteDefined('show'))
    <form id="show-{{ $model->getKey() }}"
        class="show-action"
        role="form"
        method="GET"
        action="{{ $table->getRoute('show', [$model]) }}">
        <button{{ classTag('btn', 'btn-link', 'p-0', 'text-primary', $model->disabled_classes ? 'disabled' : null) }}type="submit" title="@lang('Show')"{{ htmlAttributes($model->disabled_classes ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.show') !!}
        </button>
    </form>
@endif
