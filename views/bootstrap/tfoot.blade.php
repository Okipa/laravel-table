<tfoot>
    <tr {{ classTag($table->trClasses) }}>
        <td {{ classTag('p-0', $table->tdClasses) }}
            colspan="{{ $table->columnsCount() + ($table->isRouteDefined('edit') 
                || $table->isRouteDefined('destroy') ? 1 : 0) }}">
            <div class="d-flex justify-content-between flex-wrap">
                {{-- create button --}}
                @if($table->isRouteDefined('create'))
                    <div class="d-flex flex-fill p-3 create-container">
                        <a href="{{ $table->route('create') }}"
                           class="btn btn-success"
                           title="{{ __('laravel-table::laravel-table.create') }}">
                            {!! config('laravel-table.icon.create') !!}
                            {{ __('laravel-table::laravel-table.create') }}
                        </a>
                    </div>
                @endif
                {{-- navigation --}}
                <div {{ classTag('d-flex', 'flex-fill', 'align-items-center', 'p-3', $table->isRouteDefined('create') 
                    ? 'justify-content-center' 
                    : 'text-left', 'navigation-container') }}>
                    <div>{!! $table->navigationStatus() !!}</div>
                </div>
                {{-- pagination --}}
                <div class="d-flex flex-fill justify-content-end p-3 pagination-container">
                    {!! $table->list->links() !!}
                </div>
            </div>
        </td>
    </tr>
</tfoot>
