<?php

namespace Okipa\LaravelTable;

use Closure;

class Result
{
    public ?string $title = null;

    public ?Closure $customHtmlClosure = null;

    public array $classes = [];

    public function __construct()
    {
        $this->classes = config('laravel-table.classes.results');
    }

    /**
     * Set the result row title.
     *
     * @param string $title
     *
     * @return \Okipa\LaravelTable\Result
     */
    public function title(string $title): Result
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Display a HTML output for the result row.
     * The closure let you manipulate the following attributes : \Illuminate\Support\Collection $displayedList.
     *
     * @param \Closure $customHtmlClosure
     *
     * @return \Okipa\LaravelTable\Result
     */
    public function html(Closure $customHtmlClosure): Result
    {
        $this->customHtmlClosure = $customHtmlClosure;

        return $this;
    }

    public function getCustomHtmlClosure(): ?Closure
    {
        return $this->customHtmlClosure;
    }

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

        return $this;
    }

    public function getClasses(): array
    {
        return $this->classes;
    }
}
