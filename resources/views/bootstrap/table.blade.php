<div{{ classTag('table-container', $table->getContainerClasses()) }}>
    <table{{ htmlAttributes($table->getIdentifier() ? ['id' => $table->getIdentifier()] : null) }}{{ classTag('table', $table->getTableClasses()) }}>
        @include('laravel-table::' . $table->getTheadTemplatePath())
        @include('laravel-table::' . $table->getTbodyTemplatePath())
        @include('laravel-table::' . $table->getTfootTemplatePath())
    </table>
</div>
