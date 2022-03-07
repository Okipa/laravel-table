<?php

namespace Okipa\LaravelTable\RowActions;

use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Destroy extends AbstractRowAction
{
    public function __construct(public string $confirmationMessage)
    {
        //
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

    public function action()
    {
        $this->model->delete();
    }
}
