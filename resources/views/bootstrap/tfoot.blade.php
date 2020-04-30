<tfoot>
    <tr{{ classTag($table->getTrClasses()) }}>
        <td{{ classTag('bg-light', $table->getTdClasses()) }}{{ htmlAttributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }}>
            <div class="d-flex justify-content-between flex-wrap py-2">
                {{-- create button --}}
                @if($table->isRouteDefined('create'))
                    <div class="d-flex align-items-center px-3 py-1 creation-container">
                        <a href="{{ $table->getRoute('create') }}"
                           class="btn btn-success"
                           title="@lang('Create')">
                            {!! config('laravel-table.icon.create') !!}
                            @lang('Create')
                        </a>
                    </div>
                @endif
                {{-- navigation --}}
                <div class="d-flex align-items-center px-3 py-1 navigation-container">
                    <div>{!! $table->getNavigationStatus() !!}</div>
                </div>
                {{-- pagination --}}
                <div class="d-flex align-items-center mb-n3 px-3 py-1 pagination-container">
                    {!! $table->getPaginator()->links() !!}
                </div>
            </div>
        </td>
    </tr>
</tfoot>
