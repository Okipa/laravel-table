<thead>
    {{-- rows number / search --}}
    @if($table->getRowsNumberSelectionActivation() || ! $table->getSearchableColumns()->isEmpty())
        <tr{{ classTag($table->trClasses) }}>
            <td{{ classTag('bg-light', $table->tdClasses) }}{{ htmlAttributes($table->columnsCount() > 1 ? ['colspan' => $table->columnsCount()] : null) }}>
                <div class="d-flex flex-wrap justify-content-between py-2">
                    {{-- rows number selection --}}
                    @if($table->getRowsNumberSelectionActivation())
                        <div class="px-3 py-1 rows-number-selection">
                            <form role="form" method="GET" action="{{ $table->route('index') }}">
                                <input type="hidden" name="{{ $table->searchField }}" value="{{ $table->request->get($table->searchField) }}">
                                <input type="hidden" name="{{ $table->sortByField }}" value="{{ $table->request->get($table->sortByField) }}">
                                <input type="hidden" name="{{ $table->sortDirField }}" value="{{ $table->request->get($table->sortDirField) }}">
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
                                           name="{{ $table->rowsField }}"
                                           value="{{ $table->request->get($table->rowsField) }}"
                                           placeholder="@lang('Number of rows')"
                                           aria-label="@lang('Number of rows')">
                                    <div class="input-group-append">
                                        <div class="input-group-text py-0">
                                            <button class="btn btn-link p-0 text-primary" type="submit">
                                                {!! config('laravel-table.icon.validate') !!}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                    {{-- searching --}}
                    @if($table->getSearchableColumns()->count())
                        <div class="flex-fill px-3 py-1 searching">
                            <form role="form" method="GET" action="{{ $table->route('index') }}">
                                <input type="hidden" name="{{ $table->rowsField }}" value="{{ $table->request->get($table->rowsField) }}">
                                <input type="hidden" name="{{ $table->sortByField }}" value="{{ $table->request->get($table->sortByField) }}">
                                <input type="hidden" name="{{ $table->sortDirField }}" value="{{ $table->request->get($table->sortDirField) }}">
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
                                           name="{{ $table->searchField }}"
                                           value="{{ $table->request->get($table->searchField) }}"
                                           placeholder="@lang('Search by:') {{ $table->searchableTitles() }}"
                                           aria-label="@lang('Search by:') {{ $table->searchableTitles() }}">
                                    @if($table->request->get($table->searchField))
                                        <div class="input-group-append">
                                            <a class="input-group-text btn btn-link text-danger reset-research"
                                               href="{{ $table->route('index', array_merge([
                                                    $table->searchField    => null,
                                                    $table->rowsField      => $table->request->get($table->rowsField),
                                                    $table->sortByField    => $table->request->get($table->sortByField),
                                                    $table->sortDirField   => $table->request->get($table->sortDirField)
                                                ], $table->getAppendedValues())) }}"
                                               title="@lang('Reset research')">
                                                <span>{!! config('laravel-table.icon.reset') !!}</span>
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
                        </div>
                    @endif
                </div>
            </td>
        </tr>
    @endif
    {{-- column titles --}}
    <tr{{ classTag($table->trClasses) }}>
        @foreach($table->getColumns() as $column)
            <th{{ classTag($table->thClasses) }} scope="col">
                @if($column->isSortable)
                    <a class="d-flex"
                       href="{{ $table->route('index', array_merge([
                            $table->rowsField      => $table->request->get($table->rowsField),
                            $table->searchField    => $table->request->get($table->searchField),
                            $table->sortByField    => $column->databaseDefaultColumn,
                            $table->sortDirField   => $table->request->get($table->sortDirField) === 'desc' ? 'asc' : 'desc',
                        ], $table->getAppendedValues())) }}"
                       title="{{ $column->title }}">
                        @if($table->request->get($table->sortByField) === $column->databaseDefaultColumn
                            && $table->request->get($table->sortDirField) === 'asc')
                            <span class="sort asc">{!! config('laravel-table.icon.sortAsc') !!}</span>
                        @elseif($table->request->get($table->sortByField) === $column->databaseDefaultColumn
                            && $table->request->get($table->sortDirField) === 'desc')
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
        @if($table->isRouteDefined('show') || $table->isRouteDefined('edit') || $table->isRouteDefined('destroy'))
            <th{{ classTag('text-right', $table->thClasses) }} scope="col">
                @lang('Actions')
            </th>
        @endif
    </tr>
</thead>
