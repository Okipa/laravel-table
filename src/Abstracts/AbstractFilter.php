<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;

abstract class AbstractFilter
{
    public string $filterClass;

    public string $identifier;

    public string $modelKeyName;

    protected string|null $class;

    protected array $attributes;

    protected string $label;

    protected array $options;

    protected bool $multiple;

    abstract protected function identifier(): string;

    protected function class(): array
    {
        return [];
    }

    protected function attributes(): array
    {
        return [
            'multiple' => $this->multiple(),
            'placeholder' => $this->label(),
            'aria-label' => $this->label(),
            ...config('laravel-table.html_select_components_attributes'),
        ];
    }

    abstract protected function label(): string;

    abstract protected function options(): array;

    abstract protected function multiple(): bool;

    abstract public function filter(Builder $query, mixed $selected): void;

    public function setup(string $modelKeyName): void
    {
        $this->filterClass = $this::class;
        $this->identifier = $this->identifier();
        $this->modelKeyName = $modelKeyName;
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
        $filterInstance->modelKeyName = $filterArray['modelKeyName'];

        return $filterInstance;
    }

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.filter', [
            'filter' => $this,
            'class' => $this->class(),
            'attributes' => (new ComponentAttributeBag($this->attributes())),
            'label' => $this->label(),
            'options' => $this->options(),
            'multiple' => $this->multiple(),
        ]);
    }
}
