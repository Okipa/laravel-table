<thead class="table-header">
    {{-- rows number / search --}}
    @if(! $table->searchableColumns->isEmpty()
        || $table->rowsNumberSelectionActivation)
        <tr {{ classTag($table->trClasses) }}>
            <td {{ classTag('border-0', 'p-0', $table->tdClasses) }}
                colspan="{{ $table->columnsCount() + ($table->isRouteDefined('edit') 
                    || $table->isRouteDefined('destroy') ? 1 : 0) }}">
                <div class="d-flex justify-content-between flex-wrap">
                    {{-- rows number selector --}}
                    @if($table->rowsNumberSelectionActivation)
                        <div class="flex-fill p-3 rows-number-selector">
                            <form role="form" method="GET" action="{{ $table->route('index') }}">
                                <input type="hidden" name="search" value="{{ $table->request->search }}">
                                <input type="hidden" name="sortBy" value="{{ $table->request->sortBy }}">
                                <input type="hidden" name="sortDir" value="{{ $table->request->sortDir }}">
                                @foreach($table->appendedHiddenFields as $appendedKey => $appendedValue)
                                    <input type="hidden" name="{{ $appendedKey }}" value="{{ $appendedValue }}">
                                @endforeach
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            {!! config('laravel-table.icon.rowsNumber') !!}
                                        </span>
                                    </div>
                                    <input class="form-control"
                                           type="number"
                                           name="rows"
                                           value="{{ $table->request->rows }}"
                                           placeholder="@lang('laravel-table::laravel-table.rowsNumber')"
                                           aria-label="@lang('laravel-table::laravel-table.rowsNumber')">
                                    <div class="input-group-append">
                                        <div class="input-group-text py-0">
                                            <button class="btn btn-link text-success p-0" type="submit">
                                                {!! config('laravel-table.icon.validate') !!}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    {{-- search --}}
                    @if(! $table->searchableColumns->isEmpty())
                        <div class="flex-fill p-3 search-bar">
                            <form role="form" method="GET" action="{{ $table->route('index') }}">
                                <input type="hidden" name="rows" value="{{ $table->request->rows }}">
                                <input type="hidden" name="sortBy" value="{{ $table->request->sortBy }}">
                                <input type="hidden" name="sortDir" value="{{ $table->request->sortDir }}">
                                @foreach($table->appendedHiddenFields as $appendedKey => $appendedValue)
                                    <input type="hidden" name="{{ $appendedKey }}" value="{{ $appendedValue }}">
                                @endforeach
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            {!! config('laravel-table.icon.search') !!}
                                        </span>
                                    </div>
                                    <input class="form-control"
                                           type="text"
                                           name="search"
                                           value="{{ $table->request->search }}"
                                           placeholder="@lang('laravel-table::laravel-table.search') {{ $table->searchableTitles() }}"
                                           aria-label="@lang('laravel-table::laravel-table.search') {{ $table->searchableTitles() }}">
                                    @if($table->request->search)
                                        <div class="input-group-append">
                                            <a class="input-group-text btn btn-link text-danger"
                                               href="{{ $table->route('index', ['search' => null, 'rows' => $table->request->rows, 'sortBy' => $table->request->sortBy, 'sortDir' => $table->request->sortDir]) }}"
                                               title="@lang('laravel-table::laravel-table.cancelSearch')">
                                                <span>{!! config('laravel-table.icon.cancel') !!}</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="input-group-append">
                                            <span class="input-group-text py-0">
                                                <button class="btn btn-link text-success p-0" type="submit">
                                                    {!! config('laravel-table.icon.validate') !!}
                                                </button>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    @endif
    {{-- column titles --}}
    <tr {{ classTag($table->trClasses) }}>
        @foreach($table->columns as $column)
            <th {{ classTag('border-0', $table->thClasses) }} scope="col">
                @if($column->isSortable)
                    <a href="{{ $table->route('index', array_merge([
                                'sortBy' => $column->databaseDefaultColumn,
                                'sortDir' => $table->request->sortDir === 'desc' ? 'asc' : 'desc',
                                'search'   => $table->request->search,
                                'rows'    => $table->request->rows
                            ], $table->appendedValues)) }}"
                       title="{{ $column->title }}">
                        @if($table->request->sortBy === $column->databaseDefaultColumn 
                            && $table->request->sortDir === 'asc')
                            <span class="sort">
                                {!! config('laravel-table.icon.sortAsc') !!}
                            </span>
                        @elseif($table->request->sortBy === $column->databaseDefaultColumn 
                            && $table->request->sortDir === 'desc')
                            <span class="sort">
                                {!! config('laravel-table.icon.sortDesc') !!}
                            </span>
                        @else
                            <span class="sort">
                                {!! config('laravel-table.icon.sort') !!}
                            </span>
                        @endif
                        <span>
                            {!! str_replace(' ', '&nbsp;', $column->title) !!}
                        </span>
                    </a>
                @else
                    {!! str_replace(' ', '&nbsp;', $column->title) !!}
                @endif
            </th>
        @endforeach
        @if($table->isRouteDefined('edit') || $table->isRouteDefined('destroy'))
            <th {{ classTag('text-right', $table->thClasses) }} scope="col">
                @lang('laravel-table::laravel-table.actions')
            </th>
        @endif
    </tr>
</thead>