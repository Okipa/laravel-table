<tfoot>
    <tr{{ classTag($table->trClasses) }}>
        <td{{ classTag('bg-light', $table->tdClasses) }}{{ htmlAttributes($table->columnsCount() > 1 ? ['colspan' => $table->columnsCount()] : null) }}>
            <div class="d-flex justify-content-between flex-wrap py-2">
                {{-- create button --}}
                @if($table->isRouteDefined('create'))
                    <div class="d-flex align-items-center px-3 py-1 creation-container">
                        <a href="{{ $table->route('create') }}"
                           class="btn btn-success"
                           title="{{ __('laravel-table::laravel-table.create') }}">
                            {!! config('laravel-table.icon.create') !!}
                            {{ __('laravel-table::laravel-table.create') }}
                        </a>
                    </div>
                @endif
                {{-- navigation --}}
                <div class="d-flex align-items-center px-3 py-1 navigation-container">
                    <div>{!! $table->navigationStatus() !!}</div>
                </div>
                {{-- pagination --}}
                <div class="d-flex align-items-center mb-n3 px-3 py-1 pagination-container">
                    {!! $table->list->links() !!}
                </div>
            </div>
        </td>
    </tr>
</tfoot>
