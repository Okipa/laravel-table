<thead>
    {{-- rows number / search --}}
    @if($table->rowsNumberSelectionActivation || ! $table->searchableColumns->isEmpty())
        <tr {{ classTag($table->trClasses) }}>
            <td {{ classTag('bg-light', $table->tdClasses) }}
                {{ htmlAttributes($table->columnsCount() > 1 ? ['colspan' => $table->columnsCount()] : null) }}>
                <div class="d-flex flex-wrap justify-content-between py-2">
                    {{-- rows number selection --}}
                    <div class="px-3 py-1 rows-number-selection">
                        @if($table->rowsNumberSelectionActivation)
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
                                            <button class="btn btn-link p-0 text-primary" type="submit">
                                                {!! config('laravel-table.icon.validate') !!}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                    {{-- searching --}}
                    <div class="flex-fill px-3 py-1 searching">
                        @if(count($table->searchableColumns))
                            <form role="form" method="GET" action="{{ $table->route('index') }}">
                                <input type="hidden" name="rows" value="{{ $table->request->rows }}">
                                <input type="hidden" name="sortBy" value="{{ $table->request->sortBy }}">
                                <input type="hidden" name="sortDir" value="{{ $table->request->sortDir }}">
                                @foreach($table->appendedHiddenFields as $appendedKey => $appendedValue)
                                    <input type="hidden" name="{{ $appendedKey }}" value="{{ $appendedValue }}">
                                @endforeach
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-secondary">
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
                                            <a class="input-group-text btn btn-link text-danger cancel-search"
                                               href="{{ $table->route('index', array_merge([
                                                    'search'    => null,
                                                    'rows'      => $table->request->rows,
                                                    'sortBy'    => $table->request->sortBy,
                                                    'sortDir'   => $table->request->sortDir
                                                ], $table->appendedValues)) }}"
                                               title="@lang('laravel-table::laravel-table.cancelSearch')">
                                                <span>{!! config('laravel-table.icon.cancel') !!}</span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="input-group-append">
                                            <span class="input-group-text py-0">
                                                <button class="btn btn-link p-0 text-primary" type="submit">
                                                    {!! config('laravel-table.icon.validate') !!}
                                                </button>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    @endif
    {{-- column titles --}}
    <tr {{ classTag($table->trClasses) }}>
        @foreach($table->columns as $column)
            <th {{ classTag($table->thClasses) }} scope="col">
                @if($column->isSortable)
                    <a href="{{ $table->route('index', array_merge([
                            'sortBy'    => $column->databaseDefaultColumn,
                            'sortDir'   => $table->request->sortDir === 'desc' ? 'asc' : 'desc',
                            'search'    => $table->request->search,
                            'rows'      => $table->request->rows
                        ], $table->appendedValues)) }}"
                       title="{{ $column->title }}">
                        @if($table->request->sortBy === $column->databaseDefaultColumn 
                            && $table->request->sortDir === 'asc')
                            <span class="sort asc">{!! config('laravel-table.icon.sortAsc') !!}</span>
                        @elseif($table->request->sortBy === $column->databaseDefaultColumn 
                            && $table->request->sortDir === 'desc')
                            <span class="sort desc">{!! config('laravel-table.icon.sortDesc') !!}</span>
                        @else
                            <span class="sort">{!! config('laravel-table.icon.sort') !!}</span>
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
