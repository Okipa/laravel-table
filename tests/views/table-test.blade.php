<div>
    <table id="table-test">
        @include('laravel-table::' . $table->theadTemplatePath)
        @include('laravel-table::' . $table->tbodyTemplatePath)
        @include('laravel-table::' . $table->tfootComponentPath)
    </table>
</div>
