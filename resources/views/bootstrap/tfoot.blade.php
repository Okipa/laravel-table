<tfoot>
    <tr{{ classTag($table->getTrClasses()) }}>
        <td{{ classTag('bg-light', $table->getTdClasses()) }}{{ htmlAttributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }}>
            <div class="d-flex justify-content-between flex-wrap py-2">
                @include('laravel-table::' . $table->getNavigationStatusTemplatePath())
                @include('laravel-table::' . $table->getPaginationTemplatePath())
            </div>
        </td>
    </tr>
</tfoot>
