<tr{{ html_classes('bg-light', $table->getTrClasses()) }}>
    @foreach($table->getColumns() as $column)
        <th{{ html_classes($table->getThClasses()) }} scope="col">
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
        <th{{ html_classes('text-right', $table->getThClasses()) }} scope="col">
            @lang('Actions')
        </th>
    @endif
</tr>
