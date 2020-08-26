@if($table->isRouteDefined('show'))
    <div id="show-{{ $model->getKey() }}" class="show-action">
        <a{{ classTag('btn', 'btn-link', 'p-0', 'text-primary', $model->disabled_classes ? 'disabled' : null) }} href="{{ $table->getRoute('show', [$model]) }}" title="@lang('Show')"{{ htmlAttributes($model->disabled_classes ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.show') !!}
        </a>
    </div>
@endif
