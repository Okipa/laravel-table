@if($table->isRouteDefined('edit'))
    <form id="edit-{{ $model->getKey() }}"
        class="ml-2 edit-action"
        role="form"
        method="GET"
        action="{{ $table->getRoute('edit', [$model]) }}">
        <button{{ classTag('btn', 'btn-link', 'p-0', 'text-primary', $model->disabled_classes ? 'disabled' : null) }} type="submit" title="@lang('Edit')"{{ htmlAttributes($model->disabled_classes ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.edit') !!}
        </button>
    </form>
@endif
