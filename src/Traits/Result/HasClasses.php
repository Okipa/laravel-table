<?php

namespace Okipa\LaravelTable\Traits\Result;

use Okipa\LaravelTable\Result;

trait HasClasses
{
    public array $classes = [];

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
