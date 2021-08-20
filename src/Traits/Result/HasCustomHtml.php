<?php

namespace Okipa\LaravelTable\Traits\Result;

use Closure;
use Okipa\LaravelTable\Result;

trait HasCustomHtml
{
    protected Closure|null $customHtmlClosure = null;

    public function html(Closure $customHtmlClosure): Result
    {
        $this->customHtmlClosure = $customHtmlClosure;

        return $this;
    }

    public function getCustomHtmlClosure(): Closure|null
    {
        return $this->customHtmlClosure;
    }
}
