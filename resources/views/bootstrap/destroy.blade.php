@if($table->isRouteDefined('destroy'))
    <form id="destroy-{{ $model->getKey() }}"
          class="ml-2 destroy"
          role="form"
          method="POST"
          action="{{ $table->getRoute('destroy', [$model]) }}">
        @csrf()
        @method('DELETE')
        <button{{ classTag('btn', 'btn-link', 'p-0', 'text-danger', $model->disabledClasses ? 'disabled' : null) }} type="submit" title="@lang('Destroy')"{{ htmlAttributes($model->destroyConfirmationAttributes, $model->disabledClasses ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.destroy') !!}
        </button>
    </form>
@endif
