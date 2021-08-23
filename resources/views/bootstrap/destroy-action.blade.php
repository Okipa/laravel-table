@if($table->isRouteDefined('destroy'))
    <form id="destroy-{{ $row['id'] }}"
        class="ml-2 destroy-action"
        role="form"
        method="POST"
        action="{{ $table->getRoute('destroy', [$row['id']]) }}">
        @csrf()
        @method('DELETE')
        <button{{ html_classes('btn', 'btn-link', 'p-0', 'text-danger', data_get($row, 'disabled_classes') ? 'disabled' : null) }} type="submit" title="{{ __('Destroy') }}"{{ html_attributes(data_get($row, 'destroy_confirmation_attributes'), data_get($row, 'disabled_classes') ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.destroy') !!}
        </button>
    </form>
@endif
