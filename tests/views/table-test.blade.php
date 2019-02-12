<div>
    <table id="table-test">
        @include('laravel-table::' . $table->theadComponentPath)
        @include('laravel-table::' . $table->tbodyComponentPath)
        @include('laravel-table::' . $table->tfootComponentPath)
    </table>
</div>
