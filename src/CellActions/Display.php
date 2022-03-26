<?php

namespace Okipa\LaravelTable\CellActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Redirector;
use Okipa\LaravelTable\Abstracts\AbstractCellAction;

class Display extends AbstractCellAction
{
    public function __construct(public string $displayUrl)
    {
        //
    }

    protected function class(Model $model, string $attribute): string|null
    {
        return 'btn btn-outline-primary';
    }

    protected function title(Model $model, string $attribute): string
    {
        return __('Display');
    }

    protected function icon(Model $model, string $attribute): string
    {
        return config('laravel-table.icon.display');
    }

    protected function shouldBeConfirmed(): bool
    {
        return false;
    }

    public function action(Model $model, string $attribute, Component $livewire): Redirector
    {
        return redirect()->to($this->displayUrl);
    }
}
