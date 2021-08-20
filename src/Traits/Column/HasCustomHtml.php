<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait HasCustomHtml
{
    protected Closure|null $customHtmlClosure = null;

    public function html(Closure $customHtmlClosure): Column
    {
        $this->customHtmlClosure = $customHtmlClosure;

        return $this;
    }

    public function getCustomHtmlClosure(): Closure|null
    {
        return $this->customHtmlClosure;
    }
}
