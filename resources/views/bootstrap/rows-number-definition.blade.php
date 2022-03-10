@if($table->getRowsNumberDefinitionActivation())
    <div class="px-xl-3 py-1 rows-number-definition">
        <form role="form" method="GET" action="{{ $table->getRoute('index') }}">
            <input type="hidden" name="{{ $table->getSearchField() }}" value="{{ $table->getRequest()->get($table->getSearchField()) }}">
            <input type="hidden" name="{{ $table->getSortByField() }}" value="{{ $table->getRequest()->get($table->getSortByField()) }}">
            <input type="hidden" name="{{ $table->getSortDirField() }}" value="{{ $table->getRequest()->get($table->getSortDirField()) }}">
            @foreach($table->getGeneratedHiddenFields() as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text text-secondary">
                        {!! config('laravel-table.icon.rows_number') !!}
                    </span>
                </div>
                <input class="form-control"
                   type="number"
                   name="{{ $table->getRowsNumberField() }}"
                   value="{{ $table->getRequest()->get($table->getRowsNumberField()) }}"
                   placeholder="@lang('Number of rows')"
                   aria-label="@lang('Number of rows')">
                <div class="input-group-append">
                    <div class="input-group-text py-0">
                        <button class="btn btn-link p-0 text-primary" type="submit" title="@lang('Number of rows')">
                            {!! config('laravel-table.icon.validate') !!}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif
