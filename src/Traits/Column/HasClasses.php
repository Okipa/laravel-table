<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasClasses
{
    protected array $classes = [];

    public function classes(array $classes): Column
    {
        $this->classes = $classes;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getClasses(): array
    {
        return $this->classes;
    }
}
