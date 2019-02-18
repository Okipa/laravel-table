<tbody>
    @if($table->list->isEmpty())
        <tr {{ classTag($table->trClasses) }}>
            <td {{ classTag($table->tdClasses, 'text-center', 'p-4') }}
                colspan="{{ $table->columnsCount() + ($table->isRouteDefined('edit') 
                    || $table->isRouteDefined('destroy') ? 1 : 0) }}">
                <span class="text-info">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                </span>
                @lang('laravel-table::laravel-table.emptyTable')
            </td>
        </tr>
    @else
        @foreach($table->list as $model)
            <tr {{ classTag($table->trClasses, $model->conditionnalClasses, $model->disabledClasses) }}>
                @foreach($table->columns as $column)
                    @php
                        $value = $model->{$column->databaseDefaultColumn};
                        $customValue = $column->valueClosure ? ($column->valueClosure)($model, $column) : null;
                        $html = $column->htmlClosure ? ($column->htmlClosure)($model, $column) : null;
                        $link = $column->url instanceof Closure 
                                    ? ($column->url)($model, $column) 
                                    : ($column->url !== true 
                                        ? $column->url 
                                        : ($customValue ? $customValue : $value));
                        $showIcon = $column->icon && (($customValue || $value) || $column->displayIconWhenNoValue);
                        $showLink = $link && ($customValue || $value || $showIcon);
                        $showButton = $column->buttonClasses && ($value || $customValue || $showIcon);
                    @endphp
                    <td {{ classTag($table->tdClasses, $column->columnClasses) }}>
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
                                <button {{ classTag(
                                    $column->buttonClasses,
                                    $value ? str_slug(strip_tags($value), '-') : null,
                                    $customValue ? str_slug(strip_tags($customValue), '-') : null
                                ) }}>
                            @endif
                                {{-- icon--}}
                                @if($showIcon)
                                    {!! $column->icon !!}
                                @endif
                                {{-- custom value --}}
                                @if($customValue)
                                    {{ $customValue }}
                                {{-- string limit --}}
                                @elseif($column->stringLimit)
                                    {{ str_limit(strip_tags($value), $column->stringLimit) }}
                                {{-- datetime format --}}
                                @elseif($column->dateTimeFormat)
                                    {{ $value 
                                        ? \Carbon\Carbon::parse($value)->format($column->dateTimeFormat)
                                        : null }}
                                {{-- basic value --}}
                                @else
                                    {!! $value !!}
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
                @if(($table->isRouteDefined('edit') || $table->isRouteDefined('destroy')))
                    <td {{ classTag('text-right', $table->tdClasses) }}>
                        <div class="d-flex justify-content-end">
                            {{-- edit button --}}
                            @if($table->isRouteDefined('edit'))
                                @if(! $model->disabledClasses)
                                    <form id="edit-{{ $model->id }}"
                                          class="flex-shrink-0"
                                          role="form"
                                          method="GET"
                                          action="{{ $table->route('edit', ['id' => $model->id]) }}">
                                @endif
                                    <button {{ classTag('btn', 'btn-link', 'p-0', 'text-primary', $model->disabledClasses 
                                        ? 'disabled' 
                                        : null) }}
                                            type="submit"
                                            title="@lang('laravel-table::laravel-table.edit')"
                                            {{ htmlAttributes($model->disabledClasses 
                                                ? ['disabled' => 'disabled'] 
                                                : null) }}>
                                        {!! config('laravel-table.icon.edit') !!}
                                    </button>
                                @if(! $model->disabledClasses)
                                    </form>
                                @endif
                            @endif
                            {{-- destroy button --}}
                            @if($table->isRouteDefined('destroy'))
                                @if(! $model->disabledClasses)
                                    <form id="destroy-{{ $model->id }}"
                                          class="ml-3 destroy"
                                          role="form"
                                          method="POST"
                                          action="{{ $table->route('destroy', ['id' => $model->id]) }}">
                                        @csrf()
                                        @method('DELETE')
                                @endif
                                    <button {{ classTag('btn', 'btn-link', 'p-0', 'text-danger', $model->disabledClasses 
                                                ? 'disabled' 
                                                : null) }}
                                            type="submit"
                                            title="@lang('laravel-table::laravel-table.destroy')"
                                            {{ htmlAttributes($model->destroyConfirmationAttributes,
                                                $model->disabledClasses ? ['disabled' => 'disabled'] : null) }}>
                                        {!! config('laravel-table.icon.destroy') !!}
                                    </button>
                                @if(! $model->disabledClasses)
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    @endif
</tbody>


