<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasClasses
{
    protected array $classes = [];

    /**
     * Set the custom classes that will be applied only on this column.
     *
     * @param array $classes
     *
     * @return \Okipa\LaravelTable\Column
     */
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
