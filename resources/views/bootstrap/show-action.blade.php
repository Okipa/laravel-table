@if($table->isRouteDefined('show'))
    <div id="show-{{ $row['id'] }}" class="show-action">
        <a{{ html_classes('btn', 'btn-link', 'p-0', 'text-primary', data_get($row, 'disabled_classes') ? 'disabled' : null) }} href="{{ $table->getRoute('show', [$row['id']]) }}" title="{{ __('Show') }}"{{ html_attributes(data_get($row, 'disabled_classes') ? ['disabled' => 'disabled'] : null) }}>
            {!! config('laravel-table.icon.show') !!}
        </a>
    </div>
@endif
