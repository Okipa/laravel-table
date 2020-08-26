<thead>
    <tr{{ classTag('bg-white', $table->getTrClasses()) }}>
        <td{{ classTag('px-0', $table->getTdClasses()) }}{{ htmlAttributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }}>
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-fill">
                    @if($table->getSearchableColumns()->isNotEmpty())
                        @include('laravel-table::' . $table->getRowsSearchingTemplatePath())
                    @endif
                </div>
                <div class="d-flex justify-content-between">
                    @if($table->getRowsNumberDefinitionActivation())
                        @include('laravel-table::' . $table->getrowsNumberDefinitionTemplatePath())
                    @endif
                    @include('laravel-table::' . $table->getCreateActionTemplatePath())
                </div>
            </div>
        </td>
    </tr>
    @include('laravel-table::' . $table->getColumnTitlesTemplatePath())
</thead>
