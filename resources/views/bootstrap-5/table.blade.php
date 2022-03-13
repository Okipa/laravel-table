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
                                                    {!! Config::get('laravel-table.icon.rows_number') !!}
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
                                    {{-- Create --}}
                                    <div class="d-flex align-items-center pl-3 py-1">
                                        <a class="btn btn-success"
                                           href=""
                                           title="{{ __('Create') }}">
                                            {!! Config::get('laravel-table.icon.create') !!}
                                            {{ __('Create') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    {{-- Column titles --}}
                    <tr class="bg-light border-bottom">
                        @foreach($columns as $column)
                            <th wire:key="column-{{ Str::slug($column->getKey()) }}" class="align-middle" scope="col">
                                {{-- Sorting --}}
                                @if($column->isSortable())
                                    @if($sortBy === $column->getKey())
                                        <a wire:click.prevent="sortBy('{{ $column->getKey() }}')"
                                           href=""
                                           title="{{ $sortDir === 'asc' ? __('Sort descending') : __('Sort ascending') }}">
                                            {!! $sortDir === 'asc'
                                                ? Config::get('laravel-table.icon.sort_desc')
                                                : Config::get('laravel-table.icon.sort_asc') !!}
                                            {{ $column->getTitle() }}
                                        </a>
                                    @else
                                        <a wire:click.prevent="sortBy('{{ $column->getKey() }}')"
                                           href=""
                                           title="{{ __('Sort ascending') }}">
                                            {!! Config::get('laravel-table.icon.sort') !!}
                                            {{ $column->getTitle() }}
                                        </a>
                                    @endif
                                @else
                                    {{ $column->getTitle() }}
                                @endif
                            </th>
                        @endforeach
                        @if($tableRowActions)
                            <th wire:key="column-actions" class="align-middle" scope="col">
                                {{ __('Actions') }}
                            </th>
                        @endif
                    </tr>
                </thead>
                {{-- Table body--}}
                <tbody>
                    @forelse($rows as $row)
                        <tr wire:key="row-{{ Str::slug($row->getKey()) }}" class="border-bottom">
                            @foreach($columns as $column)
                                @if($loop->first)
                                    <th class="align-middle" scope="row">{{ $column->getValue($row) }}</th>
                                @else
                                    <td class="align-middle">{{ $column->getValue($row) }}</td>
                                @endif
                            @endforeach
                            @if($rowActions = Okipa\LaravelTable\Abstracts\AbstractRowAction::getFromModelKey($tableRowActions, $row->getKey()))
                                <td class="align-middle">
                                    @foreach($rowActions as $rowAction)
                                        {{ Okipa\LaravelTable\Abstracts\AbstractRowAction::make($rowAction)->render() }}
                                    @endforeach
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
