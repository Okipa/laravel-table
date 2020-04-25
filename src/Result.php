<?php

namespace Okipa\LaravelTable;

use Closure;

class Result
{
    public $title;
    public $htmlClosure;
    public $classes;

    /**
     * \Okipa\LaravelTable\Column constructor.
     */
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

    /**
     * Display a HTML output for the result row.
     * The closure let you manipulate the following attributes : \Illuminate\Support\Collection $displayedList.
     *
     * @param Closure $htmlClosure
     *
     * @return \Okipa\LaravelTable\Result
     */
    public function html(Closure $htmlClosure): Result
    {
        $this->htmlClosure = $htmlClosure;

        return $this;
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
}
