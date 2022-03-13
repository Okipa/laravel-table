<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;

abstract class AbstractHeadAction
{
    public string $rowActionClass;

    public string $key;

    protected string|null $class;

    protected string $title;

    protected string $icon;

    public static function make(array $rowActionArray): self
    {
        $headActionInstance = app($rowActionArray['rowActionClass'], $rowActionArray);
        $headActionInstance->rowActionClass = $rowActionArray['rowActionClass'];
        $headActionInstance->key = $rowActionArray['key'];

        return $headActionInstance;
    }

    /** @return mixed|void */
    abstract public function action();

    public function setup(): void
    {
        $this->rowActionClass = $this::class;
        $this->key = $this->key();
    }

    abstract protected function key(): string;

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.head-action', [
            'class' => $this->class(),
            'key' => $this->key,
            'title' => $this->title(),
            'icon' => $this->icon(),
        ]);
    }

    abstract protected function class(): string|null;

    abstract protected function title(): string;

    abstract protected function icon(): string;
}
