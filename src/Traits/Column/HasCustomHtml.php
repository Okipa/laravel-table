<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait HasCustomHtml
{
    protected ?Closure $customHtmlClosure = null;

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
