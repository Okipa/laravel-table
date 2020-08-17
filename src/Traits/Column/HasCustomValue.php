<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait HasCustomValue
{
    protected ?Closure $customValueClosure = null;

    public function value(Closure $customValueClosure): Column
    {
        $this->customValueClosure = $customValueClosure;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getCustomValueClosure(): ?Closure
    {
        return $this->customValueClosure;
    }
}
