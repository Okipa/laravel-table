<div wire:init="init">
    @if($table)
        <table class="table">
            {{-- Table head--}}
            <thead>
                <tr>
                    @foreach($table->getColumns() as $column)
                        <th scope="col">{{ $column->getTitle() }}</th>
                    @endforeach
                </tr>
            </thead>
            {{-- Table body--}}
            <tbody>
                @foreach($table->getRows() as $row)
                    <tr{{ $loop->first ? ' scope="row"' : null }}>
                        @foreach($table->getColumns() as $column)
                            <td>{{ data_get($row, $column->getKey()) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <tfoot>
            {!! $table->getRows()->links() !!}
        </tfoot>
    @else
        <div class="d-flex align-items-center py-3">
            <div class="spinner-border text-dark me-3" role="status">
                <span class="visually-hidden">{{ __('Loading in progress...') }}</span>
            </div>
            {{ __('Loading in progress...') }}
        </div>
    @endif
</div>
