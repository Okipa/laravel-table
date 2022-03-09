<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Destroy extends AbstractRowAction
{
    public function __construct(public string $confirmationMessage)
    {
        //
    }

    protected function class(): string
    {
        return 'text-danger';
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

    public function action(Model $model)
    {
        $model->delete();
    }
}
