@if($table->isRouteDefined('show'))
    <div id="show-{{ $model->getKey() }}" class="show-action">
        <a{{ html_classes('btn', 'btn-link', 'p-0', 'text-primary', $model->disabled_classes ? 'disabled' : null) }} href="{{ $table->getRoute('show', [$model]) }}" title="@lang('Show')"{{ html_attributes($model->disabled_classes ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.show') !!}
        </a>
    </div>
@endif
