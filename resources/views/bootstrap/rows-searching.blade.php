@if($table->getSearchableColumns()->count())
    <div class="flex-fill pr-xl-3 py-1 searching">
        <form role="form" method="GET" action="{{ $table->getRoute('index') }}">
            <input type="hidden" name="{{ $table->getRowsNumberField() }}" value="{{ $table->getRequest()->get($table->getRowsNumberField()) }}">
            <input type="hidden" name="{{ $table->getSortByField() }}" value="{{ $table->getRequest()->get($table->getSortByField()) }}">
            <input type="hidden" name="{{ $table->getSortDirField() }}" value="{{ $table->getRequest()->get($table->getSortDirField()) }}">
            @foreach($table->getGeneratedHiddenFields() as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text text-secondary">
                        {!! config('laravel-table.icon.search') !!}
                    </span>
                </div>
                <input class="form-control"
                       type="text"
                       name="{{ $table->getSearchField() }}"
                       value="{{ $table->getRequest()->get($table->getSearchField()) }}"
                       placeholder="@lang('Search by:') {{ $table->getSearchableTitles() }}"
                       aria-label="@lang('Search by:') {{ $table->getSearchableTitles() }}">
                @if($table->getRequest()->get($table->getSearchField()))
                    <div class="input-group-append">
                        <a class="input-group-text btn btn-link text-danger reset-research"
                           href="{{ $table->getRoute('index', array_merge([
                                $table->getSearchField() => null,
                                $table->getRowsNumberField() => $table->getRequest()->get($table->getRowsNumberField()),
                                $table->getSortByField() => $table->getRequest()->get($table->getSortByField()),
                                $table->getSortDirField() => $table->getRequest()->get($table->getSortDirField())
                            ], $table->getAppendedToPaginator())) }}"
                           title="@lang('Reset research')">
                            <span>{!! config('laravel-table.icon.reset') !!}</span>
                        </a>
                    </div>
                @else
                    <div class="input-group-append">
                        <span class="input-group-text py-0">
                            <button class="btn btn-link p-0 text-primary" type="submit" title="@lang('Search by:') {{ $table->getSearchableTitles() }}">
                                {!! config('laravel-table.icon.validate') !!}
                            </button>
                        </span>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endif
