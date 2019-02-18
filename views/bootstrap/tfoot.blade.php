<tfoot>
    <tr {{ classTag($table->trClasses) }}>
        <td {{ classTag('bg-light', $table->tdClasses) }}
            colspan="{{ $table->columnsCount() + ($table->isRouteDefined('edit') 
                || $table->isRouteDefined('destroy') ? 1 : 0) }}">
            <div class="d-flex justify-content-between flex-wrap py-3">
                {{-- create button --}}
                @if($table->isRouteDefined('create'))
                    <div class="d-flex px-4 py-2 creation-container">
                        <a href="{{ $table->route('create') }}"
                           class="btn btn-success"
                           title="{{ __('laravel-table::laravel-table.create') }}">
                            {!! config('laravel-table.icon.create') !!}
                            {{ __('laravel-table::laravel-table.create') }}
                        </a>
                    </div>
                @endif
                {{-- navigation --}}
                <div class="d-flex align-items-center px-4 py-2 navigation-container">
                    <div>{!! $table->navigationStatus() !!}</div>
                </div>
                {{-- pagination --}}
                <div class="d-flex px-4 py-2 pagination-container">
                    {!! $table->list->links() !!}
                </div>
            </div>
        </td>
    </tr>
</tfoot>
