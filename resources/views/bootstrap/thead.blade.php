<thead>
    @if($table->getRowsNumberDefinitionActivation() || ! $table->getSearchableColumns()->isEmpty())
        <tr{{ classTag($table->getTrClasses()) }}>
            <td{{ classTag('bg-light', $table->getTdClasses()) }}{{ htmlAttributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }}>
                <div class="d-flex flex-wrap justify-content-between py-2">
                    @include('laravel-table::' . $table->getRowsSearchingTemplatePath())
                    @include('laravel-table::' . $table->getRowsNumberSelectionTemplatePath())
                    @include('laravel-table::' . $table->getCreateActionTemplatePath())
                </div>
            </td>
        </tr>
    @endif
    @include('laravel-table::' . $table->getColumnTitlesTemplatePath())
</thead>
