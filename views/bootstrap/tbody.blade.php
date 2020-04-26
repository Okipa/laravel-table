<tbody>
    @if($table->list->isEmpty())
        <tr{{ classTag($table->trClasses) }}>
            <td{{ classTag($table->tdClasses, 'text-center', 'p-3') }}{{ htmlAttributes($table->columnsCount() > 1 ? ['colspan' => $table->columnsCount()] : null) }} scope="row">
                <span class="text-info">
                    {!! config('laravel-table.icon.info') !!}
                </span>
                @lang('laravel-table::laravel-table.emptyTable')
            </td>
        </tr>
    @else
        @foreach($table->list as $model)
            <tr{{ classTag($table->trClasses, $model->conditionnalClasses, $model->disabledClasses) }}>
                @foreach($table->columns as $columnKey => $column)
                    @php
                        $value = $model->{$column->databaseDefaultColumn};
                        $customValue = $column->valueClosure ? ($column->valueClosure)($model, $column) : null;
                        $html = $column->htmlClosure ? ($column->htmlClosure)($model, $column) : null;
                        $link = $column->url instanceof Closure ? ($column->url)($model, $column) : ($column->url !== true
                            ? $column->url
                            : ($customValue ? $customValue : $value));
                        $showPrepend = $column->prepend && (($customValue || $value) || $column->displayPrependEvenIfNoValue);
                        $showAppend = $column->append && (($customValue || $value) || $column->displayAppendEvenIfNoValue);
                        $showLink = $link && ($customValue || $value || $showPrepend || $showAppend);
                        $showButton = $column->buttonClasses && ($value || $customValue || $showPrepend || $showAppend);
                    @endphp
                    <td{{ classTag($table->tdClasses, $column->classes) }}{{ htmlAttributes($columnKey === 0 ? ['scope' => 'row'] : null) }}>
                        {{-- custom html element --}}
                        @if($html)
                            {!! $html !!}
                        @else
                            {{-- link --}}
                            @if($showLink)
                                <a href="{{ $link }}" title="{{ $customValue ? $customValue : $value }}">
                            @endif
                            {{-- button start--}}
                            @if($showButton)
                                <button{{ classTag(
                                    $column->buttonClasses,
                                    $value ? Str::slug(strip_tags($value), '-') : null,
                                    $customValue ? Str::slug(strip_tags($customValue), '-') : null
                                ) }}>
                            @endif
                                {{-- prepend --}}
                                @if($showPrepend)
                                    {!! $column->prepend !!}
                                @endif
                                {{-- custom value --}}
                                @if($customValue)
                                    {{ $customValue }}
                                {{-- string limit --}}
                                @elseif($column->stringLimit)
                                    {{ Str::limit(strip_tags($value), $column->stringLimit) }}
                                {{-- datetime format --}}
                                @elseif($column->dateTimeFormat)
                                    {{ $value
                                        ? \Carbon\Carbon::parse($value)->format($column->dateTimeFormat)
                                        : null }}
                                {{-- basic value --}}
                                @else
                                    {!! $value !!}
                                @endif
                                {{-- append --}}
                                @if($showAppend)
                                    {!! $column->append !!}
                                @endif
                            {{-- button end --}}
                            @if($showButton)
                                </button>
                            @endif
                            {{-- link end --}}
                            @if($showLink)
                                </a>
                            @endif
                        @endif
                    </td>
                @endforeach
                {{-- actions --}}
                @if(($table->isRouteDefined('edit') || $table->isRouteDefined('destroy') || $table->isRouteDefined('show')))
                    <td{{ classTag($table->tdClasses, 'text-right') }}>
                        @if(! $model->disabledClasses)
                            <div class="d-flex justify-content-end">
                                @include('laravel-table::' . $table->showTemplatePath)
                                @include('laravel-table::' . $table->editTemplatePath)
                                @include('laravel-table::' . $table->destroyTemplatePath)
                            </div>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        @include('laravel-table::' . $table->resultsComponentPath)
    @endif
</tbody>


