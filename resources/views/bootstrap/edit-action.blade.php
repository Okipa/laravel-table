@if($table->isRouteDefined('edit'))
    <div id="edit-{{ $model->getKey() }}" class="ml-2 edit-action">
        <a{{ classTag('btn', 'btn-link', 'p-0', 'text-primary', $model->disabled_classes ? 'disabled' : null) }} href="{{ $table->getRoute('edit', [$model]) }}" title="@lang('Edit')"{{ htmlAttributes($model->disabled_classes ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.edit') !!}
        </a>
    </div>
@endif
