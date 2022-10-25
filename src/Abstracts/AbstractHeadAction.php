<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Livewire\Component;

abstract class AbstractHeadAction
{
    public string $rowActionClass;

    protected bool $isAllowed = true;

    abstract protected function class(): array;

    abstract protected function icon(): string;

    abstract protected function title(): string;

    public function when(bool $condition): self
    {
        $this->isAllowed = $condition;

        return $this;
    }

    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }

    /** @return mixed|void */
    abstract public function action(Component $livewire);

    public function setup(): void
    {
        $this->rowActionClass = $this::class;
    }

    public static function make(array $rowActionArray): self
    {
        $headActionInstance = app($rowActionArray['rowActionClass'], $rowActionArray);
        $headActionInstance->rowActionClass = $rowActionArray['rowActionClass'];

        return $headActionInstance;
    }

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.head-action', [
            'class' => $this->class(),
            'title' => $this->title(),
            'icon' => $this->icon(),
        ]);
    }
}
