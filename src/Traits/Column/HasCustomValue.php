<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait HasCustomValue
{
    public Closure|null $customValueClosure = null;

    public function value(Closure $customValueClosure): Column
    {
        $this->customValueClosure = $customValueClosure;

        return $this;
    }

    public function getCustomValueClosure(): Closure|null
    {
        return $this->customValueClosure;
    }
}
