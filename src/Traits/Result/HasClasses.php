<?php

namespace Okipa\LaravelTable\Traits\Result;

use Okipa\LaravelTable\Result;

trait HasClasses
{
    public array $classes = [];

    /**
     * Override the default results classes and apply the given classes only on this result row.
     * The default result classes are managed by the config('laravel-table.classes.results') value.
     *
     * @param array $classes
     *
     * @return \Okipa\LaravelTable\Result
     */
    public function classes(array $classes): Result
    {
        $this->classes = $classes;

        /** @var \Okipa\LaravelTable\Result $this */
        return $this;
    }

    public function getClasses(): array
    {
        return $this->classes;
    }

    protected function initializeClasses()
    {
        $this->classes = config('laravel-table.classes.results');
    }
}
