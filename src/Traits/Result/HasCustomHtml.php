<?php

namespace Okipa\LaravelTable\Traits\Result;

use Closure;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Result;

trait HasCustomHtml
{
    protected ?Closure $customHtmlClosure = null;

    /**
     * Display a HTML output for the result row.
     * The closure let you manipulate the following attributes: \Illuminate\Support\Collection $displayedList.
     *
     * @param \Closure $customHtmlClosure
     *
     * @return \Okipa\LaravelTable\Result
     */
    public function html(Closure $customHtmlClosure): Result
    {
        $this->customHtmlClosure = $customHtmlClosure;

        /** @var \Okipa\LaravelTable\Result $this */
        return $this;
    }

    public function getCustomHtmlClosure(): ?Closure
    {
        return $this->customHtmlClosure;
    }
}
