@if($table->isRouteDefined('edit'))
    <div id="edit-{{ $row['id'] }}" class="ml-2 edit-action">
        <a{{ html_classes('btn', 'btn-link', 'p-0', 'text-primary', data_get($row, 'disabled_classes') ? 'disabled' : null) }} href="{{ $table->getRoute('edit', [$row['id']]) }}" title="{{ __('Edit') }}"{{ html_attributes(data_get($row, 'disabled_classes') ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.edit') !!}
        </a>
    </div>
@endif
