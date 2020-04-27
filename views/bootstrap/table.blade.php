<div{{ classTag('table-container', $table->containerClasses) }}>
    <table{{ htmlAttributes($table->getIdentifier() ? ['id' => $table->getIdentifier()] : null) }}{{ classTag('table', $table->tableClasses) }}>
        @include('laravel-table::' . $table->theadTemplatePath)
        @include('laravel-table::' . $table->tbodyTemplatePath)
        @include('laravel-table::' . $table->tfootComponentPath)
    </table>
</div>
