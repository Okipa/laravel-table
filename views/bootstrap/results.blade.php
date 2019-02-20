@foreach($table->results as $result)
    <tr {{ classTag($table->trClasses, 'result') }}>
        @if($table->columnsCount() === 1)
            <td {{ classTag($table->tdClasses, $result->classes) }} scope="row">
                <div class="d-flex align-items-center justify-content-between">
                    <span>{{ $result->title }}</span>
                    <span>{!! $result->htmlClosure ? ($result->htmlClosure)($table->list->getCollection()) : null !!}</span>
                </div>
            </td>
        @else
            <td {{ classTag($table->tdClasses, $result->classes, 'text-left') }} scope="row">
                {{ $result->title }}
            </td>
            <td {{ classTag($table->tdClasses, $result->classes, 'text-right') }} {{ htmlAttributes($table->columnsCount() > 2 ? ['colspan' => $table->columnsCount() - 1] : null) }}>
                {!! $result->htmlClosure ? ($result->htmlClosure)($table->list->getCollection()) : null !!}
            </td>
        @endif
    </tr>
@endforeach
