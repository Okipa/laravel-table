@if($table->isRouteDefined('destroy'))
    <form id="destroy-{{ $model->getKey() }}"
        class="ml-2 destroy-action"
        role="form"
        method="POST"
        action="{{ $table->getRoute('destroy', [$model]) }}">
        @csrf()
        @method('DELETE')
        <button{{ html_classes('btn', 'btn-link', 'p-0', 'text-danger', $model->disabled_classes ? 'disabled' : null) }} type="submit" title="@lang('Destroy')"{{ html_attributes($model->destroy_confirmation_attributes, $model->disabled_classes ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.destroy') !!}
        </button>
    </form>
@endif
