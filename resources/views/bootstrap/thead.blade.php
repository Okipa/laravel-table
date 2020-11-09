<thead>
    <tr{{ html_classes('bg-white', $table->getTrClasses()) }}>
        <td{{ html_classes('px-0', $table->getTdClasses()) }}{{ html_attributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }}>
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
