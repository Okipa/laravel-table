@foreach($table->getResults() as $result)
    <tr{{ html_classes($table->getTrClasses(), $result->getClasses(), 'result') }}>
        <td{{ html_classes($table->getTdClasses()) }}{{ html_attributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }} scope="row">
            <div class="d-flex align-items-center justify-content-between">
                <span>{{ $result->getTitle() }}</span>
                <span>{!! $result->getCustomHtmlClosure() ? ($result->getCustomHtmlClosure())($table->getPaginator()->getCollection()) : null !!}</span>
            </div>
        </td>
    </tr>
@endforeach
