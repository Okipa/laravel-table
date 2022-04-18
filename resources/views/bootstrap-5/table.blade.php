<div wire:init="init">
    @if($initialized)
        <div class="table-responsive">
            <table class="table table-borderless">
                {{-- Table header--}}
                <thead>
                    {{-- Table actions --}}
                    <tr class="bg-white border-bottom">
                        <td class="px-0"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                            <div class="d-flex flex-column flex-xl-row">
                                {{-- Search --}}
                                <div class="flex-fill">
                                    @if($searchableLabels)
                                        <div class="flex-fill pr-xl-3 py-1">
                                            <form wire:submit.prevent="$refresh">
                                                <div class="input-group">
                                                    <span id="search-for-rows"
                                                          class="input-group-text">
                                                        {!! config('laravel-table.icon.search') !!}
                                                    </span>
                                                    <input wire:model.defer="searchBy"
                                                           class="form-control"
                                                           placeholder="{{ __('Search by:') }} {{ $searchableLabels }}"
                                                           aria-label="{{ __('Search by:') }} {{ $searchableLabels }}"
                                                           aria-describedby="search-for-rows">
                                                    <span class="input-group-text">
                                                        <button class="btn btn-sm btn-link text-primary p-0"
                                                                type="submit"
                                                                title="{{ __('Search by:') }} {{ $searchableLabels }}">
                                                            {!! config('laravel-table.icon.validate') !!}
                                                        </button>
                                                    </span>
                                                    @if($searchBy)
                                                        <span class="input-group-text">
                                                            <a wire:click.prevent="$set('searchBy', ''), $refresh"
                                                               class="btn btn-sm btn-link text-danger p-0"
                                                               title="{{ __('Reset research') }}">
                                                                <span>{!! config('laravel-table.icon.reset') !!}</span>
                                                            </a>
                                                        </span>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between">
                                    {{-- Rows number per page --}}
                                    @if($numberOfRowsPerPageChoiceEnabled)
                                        <div class="px-xl-3 py-1">
                                            <div class="input-group">
                                                <span id="rows-number-per-page-icon"
                                                      class="input-group-text text-secondary">
                                                    {!! config('laravel-table.icon.rows_number') !!}
                                                </span>
                                                <select wire:change="changeNumberOfRowsPerPage($event.target.value)"
                                                        class="form-select"
                                                        aria-label="{{ __('Number of rows per page') }}"
                                                        aria-describedby="rows-number-per-page-icon">
                                                    @foreach($numberOfRowsPerPageOptions as $numberOfRowsPerPageOption)
                                                        <option value="{{ $numberOfRowsPerPageOption }}"{{ $numberOfRowsPerPageOption === $numberOfRowsPerPage ? ' selected' : null}}>
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
                    <tr class="bg-light border-bottom">
                        {{-- Bulk actions --}}
                        @if($tableBulkActionsArray)
                            <th wire:key="bulk-actions" class="align-middle" scope="col">
                                {{-- Bulk actions select all --}}
                                <input wire:model="selectAllRowsForBulkAction" type="checkbox">
                                {{-- Bulk actions dropdown --}}
                                <div class="dropdown">
                                    <a id="bulk-actions-dropdown"
                                       class="dropdown-toggle"
                                       type="button"
                                       data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="bulk-actions-dropdown">
                                        @foreach($tableBulkActionsArray as $bulkActionArray)
                                            {{ Okipa\LaravelTable\Abstracts\AbstractBulkAction::make($bulkActionArray)->render() }}
                                        @endforeach
                                    </ul>
                                </div>
                            </th>
                        @endif
                        {{-- Sorting --}}
                        @foreach($columns as $column)
                            <th wire:key="column-{{ Str::slug($column->getKey()) }}" class="align-middle" scope="col">
                                @if($column->isSortable())
                                    @if($sortBy === $column->getKey())
                                        <a wire:click.prevent="sortBy('{{ $column->getKey() }}')"
                                           href=""
                                           title="{{ $sortDir === 'asc' ? __('Sort descending') : __('Sort ascending') }}">
                                            {!! $sortDir === 'asc'
                                                ? config('laravel-table.icon.sort_desc')
                                                : config('laravel-table.icon.sort_asc') !!}
                                            {{ $column->getTitle() }}
                                        </a>
                                    @else
                                        <a wire:click.prevent="sortBy('{{ $column->getKey() }}')"
                                           href=""
                                           title="{{ __('Sort ascending') }}">
                                            {!! config('laravel-table.icon.sort') !!}
                                            {{ $column->getTitle() }}
                                        </a>
                                    @endif
                                @else
                                    {{ $column->getTitle() }}
                                @endif
                            </th>
                        @endforeach
                        {{-- Actions --}}
                        @if($tableRowActionsArray)
                            <th wire:key="row-actions" class="align-middle text-end" scope="col">
                                {{ __('Actions') }}
                            </th>
                        @endif
                    </tr>
                </thead>
                {{-- Table body--}}
                <tbody>
                    @forelse($rows as $model)
                        <tr wire:key="row-{{ Str::slug($model->getKey()) }}" @class(array_merge(Arr::get($tableRowClass, $model->getKey(), []), ['border-bottom']))>
                            {{-- Row bulk action selector --}}
                            @if($tableBulkActionsArray)
                                <td class="align-middle">
                                    <input wire:model="selectedModelKeys" type="checkbox" value="{{ $model->getKey() }}">
                                </td>
                            @endif
                            {{-- Row columns values --}}
                            @foreach($columns as $column)
                                @if($loop->first)
                                    <th class="align-middle" scope="row">{{ $column->getValue($model, $tableColumnActionsArray) }}</th>
                                @else
                                    <td class="align-middle">{{ $column->getValue($model, $tableColumnActionsArray) }}</td>
                                @endif
                            @endforeach
                            {{-- Row actions --}}
                            @if($tableRowActionsArray)
                                <td class="align-middle text-end">
                                    @if($rowActionsArray = Okipa\LaravelTable\Abstracts\AbstractRowAction::retrieve($tableRowActionsArray, $model->getKey()))
                                        @foreach($rowActionsArray as $rowActionArray)
                                            {{ Okipa\LaravelTable\Abstracts\AbstractRowAction::make($rowActionArray)->render($model) }}
                                        @endforeach
                                    @endif
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
                <tfoot>
                    <tr>
                        <td class="bg-light align-middle"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-center px-3 py-1">
                                    <div>{!! $navigationStatus !!}</div>
                                </div>
                                <div class="d-flex align-items-center mb-n3 px-3 py-1">
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
            <div class="spinner-border text-dark me-3" role="status">
                <span class="visually-hidden">{{ __('Loading in progress...') }}</span>
            </div>
            {{ __('Loading in progress...') }}
        </div>
    @endif
</div>
