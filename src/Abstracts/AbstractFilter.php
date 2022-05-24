<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    public string $filterClass;

    public string $identifier;

    protected string|null $class;

    protected string $label;

    protected array $options;

    protected bool $multiple;

    abstract protected function identifier(): string;

    abstract protected function class(): string|null;

    abstract protected function label(): string;

    abstract protected function options(): array;

    abstract protected function multiple(): bool;

    abstract public function filter(Builder $query, mixed $selected): void;

    public function setup(): void
    {
        $this->filterClass = $this::class;
        $this->identifier = $this->identifier();
    }

    public static function retrieve(array $filtersArray, string $identifier): array
    {
        return collect($filtersArray)->firstOrFail('identifier', $identifier);
    }

    public static function make(array $filterArray): self
    {
        /** @var \Okipa\LaravelTable\Abstracts\AbstractFilter $filterInstance */
        $filterInstance = app($filterArray['filterClass'], $filterArray);
        $filterInstance->filterClass = $filterArray['filterClass'];
        $filterInstance->identifier = $filterArray['identifier'];

        return $filterInstance;
    }

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.filter', [
            'filter' => $this,
            'class' => $this->class(),
            'label' => $this->label(),
            'options' => $this->options(),
            'multiple' => $this->multiple(),
        ]);
    }
}
