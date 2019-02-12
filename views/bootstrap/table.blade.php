<div {{ classTag('table-container', $table->containerClasses) }}>
    <table {{ classTag('table', $table->tableClasses) }}>
        @include('laravel-table::' . $table->theadComponentPath)
        @include('laravel-table::' . $table->tbodyComponentPath)
        @include('laravel-table::' . $table->tfootComponentPath)
    </table>
</div>
