<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait HasCustomValue
{
    protected ?Closure $customValueClosure = null;

    /**
     * Display a custom value for the column.
     * The closure let you manipulate the following attributes: \Illuminate\Database\Eloquent\Model $model,
     * \Okipa\LaravelTable\Column$column.
     *
     * @param \Closure $customValueClosure
     *
     * @return \Okipa\LaravelTable\Column
     */
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
