<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Destroy extends AbstractRowAction
{
    public function action(Model $model, Component $livewire): void
    {
        $model->delete();
    }

    protected function class(): string
    {
        return 'link-danger';
    }

    protected function key(): string
    {
        return 'destroy';
    }

    protected function title(): string
    {
        return __('Destroy');
    }

    protected function icon(): string
    {
        return config('laravel-table.icon.destroy');
    }

    protected function shouldBeConfirmed(): bool
    {
        return true;
    }
}
