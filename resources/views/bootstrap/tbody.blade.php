<tbody>
    @if($table->getPaginator()->isEmpty())
        <tr{{ html_classes($table->getTrClasses()) }}>
            <td{{ html_classes($table->getTdClasses(), 'text-center', 'p-3') }}{{ html_attributes($table->getColumnsCount() > 1 ? ['colspan' => $table->getColumnsCount()] : null) }} scope="row">
                <span class="text-info">
                    {!! config('laravel-table.icon.info') !!}
                </span>
                @lang('No results were found.')
            </td>
        </tr>
    @else
        @foreach($table->getPaginator() as $model)
            <tr{{ html_classes($table->getTrClasses(), $model->conditionnal_classes, $model->disabled_classes) }}>
                @foreach($table->getColumns() as $columnKey => $column)
                    @php
                        $value = $model->{$column->getDbField()};
                        $customValue = $column->getCustomValueClosure() ? ($column->getCustomValueClosure())($model) : null;
                        $html = $column->getCustomHtmlClosure() ? ($column->getCustomHtmlClosure())($model) : null;
                        $url = $column->getUrlClosure()
                            ? ($column->getUrlClosure())($model)
                            : ($column->getUrl() === '__VALUE__' ? ($customValue ? $customValue : $value) : $column->getUrl());
                        $showPrepend = $column->getPrependedHtml() && (($customValue || $value) || $column->shouldForcePrependedHtmlDisplay());
                        $showAppend = $column->getAppendedHtml() && (($customValue || $value) || $column->shouldForceAppendedHtmlDisplay());
                        $showLink = $url && ($customValue || $value || $showPrepend || $showAppend);
                        $showButton = $column->getButtonClasses() && ($value || $customValue || $showPrepend || $showAppend);
                    @endphp
                    <td{{ html_classes($table->getTdClasses(), $column->getClasses()) }}{{ html_attributes($columnKey === 0 ? ['scope' => 'row'] : null) }}>
                        {{-- Custom html element --}}
                        @if($html)
                            {!! $html !!}
                        @else
                            {{-- Link --}}
                            @if($showLink)
                                <a href="{{ $url }}" title="{{ $customValue ? $customValue : $value }}">
                            @endif
                            {{-- Button start--}}
                            @if($showButton)
                                <button{{ html_classes(
                                    $column->getButtonClasses(),
                                    $value ? Str::slug(strip_tags($value), '-') : null,
                                    $customValue ? Str::slug(strip_tags($customValue), '-') : null
                                ) }}>
                            @endif
                                {{-- Prepend --}}
                                @if($showPrepend)
                                    {!! $column->getPrependedHtml() !!}
                                @endif
                                {{-- Custom value --}}
                                @if($customValue)
                                    {{ $customValue }}
                                {{-- String limit --}}
                                @elseif($column->getStringLimit())
                                    {{ Str::limit(strip_tags($value), $column->getStringLimit()) }}
                                {{-- Datetime format --}}
                                @elseif($column->getDateTimeFormat())
                                    {{ $value
                                        ? \Carbon\Carbon::parse($value)->timezone($column->getTimezone())->format($column->getDateTimeFormat())
                                        : null }}
                                {{-- Basic value --}}
                                @else
                                    {{ $value }}
                                @endif
                                {{-- Append --}}
                                @if($showAppend)
                                    {!! $column->getAppendedHtml() !!}
                                @endif
                            {{-- Button end --}}
                            @if($showButton)
                                </button>
                            @endif
                            {{-- Link end --}}
                            @if($showLink)
                                </a>
                            @endif
                        @endif
                    </td>
                @endforeach
                {{-- Actions --}}
                @if(($table->isRouteDefined('edit') || $table->isRouteDefined('destroy') || $table->isRouteDefined('show')))
                    <td{{ html_classes($table->getTdClasses(), 'text-right') }}>
                        @if(! $model->disabled_classes)
                            <div class="d-flex justify-content-end">
                                @include('laravel-table::' . $table->getShowActionTemplatePath())
                                @include('laravel-table::' . $table->getEditActionTemplatePath())
                                @include('laravel-table::' . $table->getDestroyActionTemplatePath())
                            </div>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        @include('laravel-table::' . $table->getResultsTemplatePath())
    @endif
</tbody>


