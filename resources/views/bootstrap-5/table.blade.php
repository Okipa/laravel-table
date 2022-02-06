<div wire:init="init">
    @if($initialized)
        <div class="table-responsive">
            <table class="table table-borderless">
                {{-- Table header--}}
                <thead>
                    {{-- Table actions --}}
                    <tr class="bg-white">
                        <td class="px-0"{{ $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null }}>
                            <div class="d-flex flex-column flex-xl-row">
                                {{-- Search --}}
                                <div class="flex-fill">
                                </div>
                                <div class="d-flex justify-content-between">
                                    {{-- Rows number per page --}}
                                    <div class="px-xl-3 py-1">
                                        <div class="input-group">
                                            <span id="rows-number-per-page-icon" class="input-group-text text-secondary">
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
                    <tr>
                        @foreach($columns as $column)
                            <th scope="col">{{ $column->getTitle() }}</th>
                        @endforeach
                    </tr>
                </thead>
                {{-- Table body--}}
                <tbody>
                    @foreach($rows as $row)
                        <tr{{ $loop->first ? ' scope="row"' : null }}>
                            @foreach($columns as $column)
                                <td>{{ data_get($row, $column->getKey()) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                {{-- Table footer--}}
                <tfoot>
                    <tr>
                        <td class="bg-light"{{ $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null }}>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-center px-3 py-1 navigation-container">
                                    <div>{!! $navigationStatus !!}</div>
                                </div>
                                <div class="d-flex align-items-center mb-n3 px-3 py-1 pagination-container">
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
