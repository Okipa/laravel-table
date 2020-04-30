<div>
    <table id="table-test">
        @include('laravel-table::' . $table->getTheadTemplatePath())
        @include('laravel-table::' . $table->getTbodyTemplatePath())
        @include('laravel-table::' . $table->getTfootTemplatePath())
    </table>
</div>
