<thead>
    @if($table->getRowsNumberDefinitionActivation() || ! $table->getSearchableColumns()->isEmpty())
        <tr{{ classTag($table->getTrClasses()) }}>
            <td{{ classTag('bg-light', $table->getTdClasses()) }}{{ htmlAttributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }}>
                <div class="d-flex flex-column flex-xl-row py-2">
                    <div class="flex-fill">
                        @include('laravel-table::' . $table->getRowsSearchingTemplatePath())
                    </div>
                    <div class="d-flex justify-content-between">
                        @include('laravel-table::' . $table->getRowsNumberSelectionTemplatePath())
                        @include('laravel-table::' . $table->getCreateActionTemplatePath())
                    </div>
                </div>
            </td>
        </tr>
    @endif
    @include('laravel-table::' . $table->getColumnTitlesTemplatePath())
</thead>
