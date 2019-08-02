@foreach($table->results as $result)
    <tr{{ classTag($table->trClasses, $result->classes, 'result') }}>
        <td{{ classTag($table->tdClasses) }}{{ htmlAttributes($table->columnsCount() > 1 ? ['colspan' => $table->columnsCount()] : null) }} scope="row">
            <div class="d-flex align-items-center justify-content-between">
                <span>{{ $result->title }}</span>
                <span>{!! $result->htmlClosure ? ($result->htmlClosure)($table->list->getCollection()) : null !!}</span>
            </div>
        </td>
    </tr>
@endforeach
