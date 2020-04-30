<thead>
    {{-- rows number / search --}}
    @if($table->getRowsNumberSelectionActivation() || ! $table->getSearchableColumns()->isEmpty())
        <tr{{ classTag($table->getTrClasses()) }}>
            <td{{ classTag('bg-light', $table->getTdClasses()) }}{{ htmlAttributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }}>
                <div class="d-flex flex-wrap justify-content-between py-2">
                    {{-- rows number selection --}}
                    @if($table->getRowsNumberSelectionActivation())
                        <div class="px-3 py-1 rows-number-selection">
                            <form role="form" method="GET" action="{{ $table->getRoute('index') }}">
                                <input type="hidden" name="{{ $table->getSearchField() }}" value="{{ $table->getRequest()->get($table->getSearchField()) }}">
                                <input type="hidden" name="{{ $table->getSortByField() }}" value="{{ $table->getRequest()->get($table->getSortByField()) }}">
                                <input type="hidden" name="{{ $table->getSortDirField() }}" value="{{ $table->getRequest()->get($table->getSortDirField()) }}">
                                @foreach($table->getGeneratedHiddenFields() as $appendedKey => $appendedValue)
                                    <input type="hidden" name="{{ $appendedKey }}" value="{{ $appendedValue }}">
                                @endforeach
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
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
                    {{-- searching --}}
                    @if($table->getSearchableColumns()->count())
                        <div class="flex-fill px-3 py-1 searching">
                            <form role="form" method="GET" action="{{ $table->getRoute('index') }}">
                                <input type="hidden" name="{{ $table->getRowsNumberField() }}" value="{{ $table->getRequest()->get($table->getRowsNumberField()) }}">
                                <input type="hidden" name="{{ $table->getSortByField() }}" value="{{ $table->getRequest()->get($table->getSortByField()) }}">
                                <input type="hidden" name="{{ $table->getSortDirField() }}" value="{{ $table->getRequest()->get($table->getSortDirField()) }}">
                                @foreach($table->getGeneratedHiddenFields() as $appendedKey => $appendedValue)
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
                </div>
            </td>
        </tr>
    @endif
    {{-- column titles --}}
    <tr{{ classTag($table->getTrClasses()) }}>
        @foreach($table->getColumns() as $column)
            <th{{ classTag($table->getThClasses()) }} scope="col">
                @if($column->getIsSortable())
                    <a class="d-flex"
                       href="{{ $table->getRoute('index', array_merge([
                            $table->getRowsNumberField() => $table->getRequest()->get($table->getRowsNumberField()),
                            $table->getSearchField() => $table->getRequest()->get($table->getSearchField()),
                            $table->getSortByField() => $column->getDbField(),
                            $table->getSortDirField() => $table->getRequest()->get($table->getSortDirField()) === 'desc' ? 'asc' : 'desc',
                        ], $table->getAppendedToPaginator())) }}"
                       title="{{ $column->getTitle() }}">
                        @if($table->getRequest()->get($table->getSortByField()) === $column->getDbField()
                            && $table->getRequest()->get($table->getSortDirField()) === 'asc')
                            <span class="sort asc">{!! config('laravel-table.icon.sort_asc') !!}</span>
                        @elseif($table->getRequest()->get($table->getSortByField()) === $column->getDbField()
                            && $table->getRequest()->get($table->getSortDirField()) === 'desc')
                            <span class="sort desc">{!! config('laravel-table.icon.sort_desc') !!}</span>
                        @else
                            <span class="sort">{!! config('laravel-table.icon.sort') !!}</span>
                        @endif
                        <span>
                            {!! str_replace(' ', '&nbsp;', $column->getTitle()) !!}
                        </span>
                    </a>
                @else
                    {!! str_replace(' ', '&nbsp;', $column->getTitle()) !!}
                @endif
            </th>
        @endforeach
        @if($table->isRouteDefined('show') || $table->isRouteDefined('edit') || $table->isRouteDefined('destroy'))
            <th{{ classTag('text-right', $table->getThClasses()) }} scope="col">
                @lang('Actions')
            </th>
        @endif
    </tr>
</thead>
