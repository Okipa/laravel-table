<div wire:init="init">
    @if($initialized)
        @if($orderColumn)
            <div class="alert alert-info" role="alert">
                {{ __('You can rearrange the order of the items in this list using a drag and drop action.') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-borderless">
                {{-- Table header--}}
                <thead>
                    {{-- Filters --}}
                    @if($filtersArray)
                        <tr>
                            <td class="px-0 pb-0"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                                <div class="d-flex flex-wrap align-items-center justify-content-end mt-n2">
                                    <div class="text-secondary mt-2">
                                        {!! config('laravel-table.icon.filter') !!}
                                    </div>
                                    @foreach($filtersArray as $filterArray)
                                        @unless($resetFilters)
                                            <div wire:ignore>
                                        @endif
                                            {!! Okipa\LaravelTable\Abstracts\AbstractFilter::make($filterArray)->render() !!}
                                        @unless($resetFilters)
                                            </div>
                                        @endif
                                    @endforeach
                                    @if(collect($this->selectedFilters)->filter(fn(mixed $filter) => isset($filter) && $filter !== '' && $filter !== [])->isNotEmpty())
                                        <a wire:click.prevent="resetFilters()"
                                           class="btn btn-outline-secondary ml-3 mt-2"
                                           title="{{ __('Reset filters') }}"
                                           data-toggle="tooltip">
                                            {!! config('laravel-table.icon.reset') !!}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                    {{-- Search/Number of rows per page/Head action --}}
                    <tr>
                        <td class="px-0"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                            <div class="d-flex flex-column flex-xl-row">
                                {{-- Search --}}
                                <div class="flex-fill">
                                    @if($searchableLabels)
                                        <div class="flex-fill pr-xl-3 py-1">
                                            <form wire:submit.prevent="$refresh">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span id="search-for-rows" class="input-group-text">
                                                            {!! config('laravel-table.icon.search') !!}
                                                        </span>
                                                    </div>
                                                    <input wire:model.defer="searchBy"
                                                           class="form-control"
                                                           placeholder="{{ __('Search by:') }} {{ $searchableLabels }}"
                                                           aria-label="{{ __('Search by:') }} {{ $searchableLabels }}"
                                                           aria-describedby="search-for-rows">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <button class="btn btn-sm btn-link link-primary p-0"
                                                                    type="submit"
                                                                    title="{{ __('Search by:') }} {{ $searchableLabels }}">
                                                                {!! config('laravel-table.icon.validate') !!}
                                                            </button>
                                                        </span>
                                                    </div>
                                                    @if($searchBy)
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <a wire:click.prevent="$set('searchBy', ''), $refresh"
                                                                   class="btn btn-sm btn-link text-secondary p-0"
                                                                   title="{{ __('Reset research') }}">
                                                                    {!! config('laravel-table.icon.reset') !!}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between">
                                    {{-- Number of rows per page --}}
                                    @if($numberOfRowsPerPageChoiceEnabled)
                                        <div wire:ignore @class(['px-xl-3' => $headActionArray, 'pl-xl-3' => ! $headActionArray, 'py-1'])>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span id="rows-number-per-page-icon" class="input-group-text text-secondary">
                                                        {!! config('laravel-table.icon.rows_number') !!}
                                                    </span>
                                                </div>
                                                <select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="custom-select" {!! (new \Illuminate\View\ComponentAttributeBag())->merge([
                                                    'placeholder' => __('Number of rows per page'),
                                                    'aria-label' => __('Number of rows per page'),
                                                    'aria-describedby' => 'rows-number-per-page-icon',
                                                    ...config('laravel-table.html_select_components_attributes'),
                                                ])->toHtml() !!}>
                                                    <option wire:key="rows-number-per-page-option-placeholder" value="" disabled>{{ __('Number of rows per page') }}</option>
                                                    @foreach($numberOfRowsPerPageOptions as $numberOfRowsPerPageOption)
                                                        <option wire:key="rows-number-per-page-option-{{ $numberOfRowsPerPageOption }}" value="{{ $numberOfRowsPerPageOption }}"{{ $numberOfRowsPerPageOption === $numberOfRowsPerPage ? ' selected' : null}}>
                                                            {{ $numberOfRowsPerPageOption }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Head action --}}
                                    @if($headActionArray)
                                        <div class="d-flex align-items-center pl-3 py-1">
                                            {{ Okipa\LaravelTable\Abstracts\AbstractHeadAction::make($headActionArray)->render() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    {{-- Column headings --}}
                    <tr class="table-light border-top border-bottom">
                        {{-- Bulk actions --}}
                        @if($tableBulkActionsArray)
                            <th wire:key="bulk-actions" class="align-middle" scope="col">
                                <div class="d-flex align-items-center">
                                    {{-- Bulk actions select all --}}
                                    <input wire:model="selectAll" class="mr-1" type="checkbox" aria-label="Check all displayed lines">
                                    {{-- Bulk actions dropdown --}}
                                    <div class="dropdown" title="{{ __('Bulk Actions') }}" data-toggle="tooltip">
                                        <a id="bulk-actions-dropdown"
                                           class="dropdown-toggle"
                                           type="button"
                                           data-toggle="dropdown"
                                           aria-expanded="false">
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="bulk-actions-dropdown">
                                            @foreach($tableBulkActionsArray as $bulkActionArray)
                                                {{ Okipa\LaravelTable\Abstracts\AbstractBulkAction::make($bulkActionArray)->render() }}
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </th>
                        @endif
                        {{-- Sorting/Column titles --}}
                        @foreach($columns as $column)
                            <th wire:key="column-{{ Str::of($column->getAttribute())->snake('-')->slug() }}" class="align-middle" scope="col">
                                @if($column->isSortable($orderColumn))
                                    @if($sortBy === $column->getAttribute())
                                        <a wire:click.prevent="sortBy('{{ $column->getAttribute() }}')"
                                           class="d-flex align-items-center"
                                           href=""
                                           title="{{ $sortDir === 'asc' ? __('Sort descending') : __('Sort ascending') }}"
                                           data-toggle="tooltip">
                                            {!! $sortDir === 'asc'
                                                ? config('laravel-table.icon.sort_desc')
                                                : config('laravel-table.icon.sort_asc') !!}
                                            <span class="ml-2">{{ $column->getTitle() }}</span>
                                        </a>
                                    @else
                                        <a wire:click.prevent="sortBy('{{ $column->getAttribute() }}')"
                                           class="d-flex align-items-center"
                                           href=""
                                           title="{{ __('Sort ascending') }}"
                                           data-toggle="tooltip">
                                            {!! config('laravel-table.icon.sort') !!}
                                            <span class="ml-2">{{ $column->getTitle() }}</span>
                                        </a>
                                    @endif
                                @else
                                    {{ $column->getTitle() }}
                                @endif
                            </th>
                        @endforeach
                        {{-- Row actions --}}
                        @if($tableRowActionsArray)
                            <th wire:key="row-actions" class="align-middle text-end" scope="col">
                                {{ __('Actions') }}
                            </th>
                        @endif
                    </tr>
                </thead>
                {{-- Table body--}}
                <tbody{!! $orderColumn ? ' wire:sortable="reorder"' : null !!}>
                    {{-- Rows --}}
                    @forelse($rows as $model)
                        <tr wire:key="row-{{ $model->getKey() }}"{!! $orderColumn ? ' wire:sortable.item="' . $model->getKey() . '"' : null !!} @class(array_merge(Arr::get($tableRowClass, $model->laravel_table_unique_identifier, []), ['border-bottom']))>
                            {{-- Row bulk action selector --}}
                            @if($tableBulkActionsArray)
                                <td class="align-middle">
                                    <input wire:model="selectedModelKeys" type="checkbox" value="{{ $model->getKey() }}" aria-label="Check line {{ $model->getKey() }}">
                                </td>
                            @endif
                            {{-- Row columns values --}}
                            @foreach($columns as $column)
                                @if($loop->first)
                                    <th wire:key="cell-{{ Str::of($column->getAttribute())->snake('-')->slug() }}-{{ $model->getKey() }}"{!! $orderColumn ? ' wire:sortable.handle style="cursor: move;"' : null !!} class="align-middle" scope="row">
                                        {!! $orderColumn ? '<span class="mr-2">' . config('laravel-table.icon.drag_drop') . '</span>' : null !!}{{ $column->getValue($model, $tableColumnActionsArray) }}
                                    </th>
                                @else
                                    <td wire:key="cell-{{ Str::of($column->getAttribute())->snake('-')->slug() }}-{{ $model->getKey() }}" class="align-middle">
                                        {{ $column->getValue($model, $tableColumnActionsArray) }}
                                    </td>
                                @endif
                            @endforeach
                            {{-- Row actions --}}
                            @if($tableRowActionsArray)
                                <td class="align-middle text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        @if($rowActionsArray = Okipa\LaravelTable\Abstracts\AbstractRowAction::retrieve($tableRowActionsArray, $model->getKey()))
                                            @foreach($rowActionsArray as $rowActionArray)
                                                {{ Okipa\LaravelTable\Abstracts\AbstractRowAction::make($rowActionArray)->render($model) }}
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="border-bottom">
                            <th class="fw-normal text-center align-middle p-3" scope="row"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                                <span class="text-info">
                                    {!! config('laravel-table.icon.info') !!}
                                </span>
                                {{ __('No results were found.') }}
                            </th>
                        </tr>
                    @endforelse
                </tbody>
                {{-- Table footer--}}
                <tfoot class="table-light">
                    {{-- Results --}}
                    @foreach($results as $result)
                        <tr wire:key="result-{{ Str::of($result->getTitle())->snake('-')->slug() }}" class="border-bottom">
                            <td class="align-middle fw-bold"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="px-2 py-1">{{ $result->getTitle() }}</div>
                                    <div class="px-2 py-1">{{ $result->getValue() }}</div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="align-middle"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="d-flex align-items-center p-2">
                                    <div>{!! $navigationStatus !!}</div>
                                </div>
                                <div class="d-flex align-items-center mb-n3 p-2">
                                    {!! $rows->links() !!}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="d-flex align-items-center py-3">
            <div class="spinner-border text-dark mr-3" role="status">
                <span class="sr-only">{{ __('Loading in progress...') }}</span>
            </div>
            {{ __('Loading in progress...') }}
        </div>
    @endif
</div>
