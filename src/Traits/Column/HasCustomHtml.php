<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait HasCustomHtml
{
    protected ?Closure $customHtmlClosure = null;

    /**
     * Display a custom HTML for the column.
     * The closure let you manipulate the following attributes : \Illuminate\Database\Eloquent\Model $model,
     * \Okipa\LaravelTable\Column $column.
     *
     * @param \Closure $customHtmlClosure
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function html(Closure $customHtmlClosure): Column
    {
        $this->customHtmlClosure = $customHtmlClosure;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getCustomHtmlClosure(): ?Closure
    {
        return $this->customHtmlClosure;
    }
}
