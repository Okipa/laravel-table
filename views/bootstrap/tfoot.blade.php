<tfoot>
    <tr {{ classTag($table->trClasses) }}>
        <td {{ classTag('py-4', $table->tdClasses) }}
            colspan="{{ $table->columnsCount() + ($table->isRouteDefined('edit') || $table->isRouteDefined('destroy') ? 1 : 0) }}">
            <div class="row">
                {{-- create button --}}
                @if($table->isRouteDefined('create'))
                    <div class="d-flex col-sm-4 create-container">
                        <a href="{{ $table->route('create') }}"
                           class="btn btn-success"
                           title="{{ __('laravel-table::laravel-table.create') }}">
                            {!! config('laravel-table.icon.create') !!}
                            {{ __('laravel-table::laravel-table.create') }}
                        </a>
                    </div>
                @endif
                {{-- navigation --}}
                <div {{ classTag('d-flex', 'align-items-center', $table->isRouteDefined('create') ? ['col-sm-4', 'justify-content-center'] : ['col-sm-6', 'text-left'], 'navigation-container') }}>
                    <div>{!! $table->navigationStatus() !!}</div>
                </div>
                {{-- pagination --}}
                <div {{ classTag('d-flex', 'justify-content-end', $table->isRouteDefined('create') ? 'col-sm-4' : 'col-sm-6 text-right', 'pagination-container') }}>
                    {!! $table->list->links() !!}
                </div>
            </div>
        </td>
    </tr>
</tfoot>
