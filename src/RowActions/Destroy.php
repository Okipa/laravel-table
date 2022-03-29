<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Destroy extends AbstractRowAction
{
    protected function identifier(): string
    {
        return 'destroy';
    }

    protected function class(Model $model): string
    {
        return 'link-danger';
    }

    protected function icon(Model $model): string
    {
        return config('laravel-table.icon.destroy');
    }

    protected function title(Model $model): string
    {
        return __('Destroy');
    }

    protected function shouldBeConfirmed(): bool
    {
        return true;
    }

    public function action(Model $model, Component $livewire): void
    {
        $model->delete();
    }
}
