@if($table->isRouteDefined('show'))
    <form id="show-{{ $model->getKey() }}"
          role="form"
          method="GET"
          action="{{ $table->getRoute('show', [$model]) }}">
        <button{{ classTag('btn', 'btn-link', 'p-0', 'text-primary', $model->disabledClasses ? 'disabled' : null) }}type="submit" title="@lang('Show')"{{ htmlAttributes($model->disabledClasses ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.show') !!}
        </button>
    </form>
@endif
