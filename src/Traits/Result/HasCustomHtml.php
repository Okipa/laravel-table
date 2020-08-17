<?php

namespace Okipa\LaravelTable\Traits\Result;

use Closure;
use Okipa\LaravelTable\Result;

trait HasCustomHtml
{
    protected ?Closure $customHtmlClosure = null;

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
