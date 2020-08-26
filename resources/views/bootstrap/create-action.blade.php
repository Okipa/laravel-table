@if($table->isRouteDefined('create'))
    <div class="d-flex align-items-center pl-3 py-1 create-action">
        <a href="{{ $table->getRoute('create') }}"
           class="btn btn-success"
           title="@lang('Create')">
            {!! config('laravel-table.icon.create') !!}
            @lang('Create')
        </a>
    </div>
@endif
