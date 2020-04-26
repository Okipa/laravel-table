@if($table->isRouteDefined('edit'))
    <form id="edit-{{ $model->getKey() }}"
          class="ml-2"
          role="form"
          method="GET"
          action="{{ $table->route('edit', [$model]) }}">
        <button{{ classTag('btn', 'btn-link', 'p-0', 'text-primary', $model->disabledClasses ? 'disabled' : null) }} type="submit" title="@lang('laravel-table::laravel-table.edit')"{{ htmlAttributes($model->disabledClasses ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.edit') !!}
        </button>
    </form>
@endif
