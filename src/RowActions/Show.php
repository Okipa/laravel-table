<?php

namespace Okipa\LaravelTable\RowActions;

use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Show extends AbstractRowAction
{
    public function __construct(protected string $showRouteName)
    {
        //
    }

    public function action(): mixed
    {
        return redirect()->route($this->showRouteName, $this->model);
    }

    protected function icon(): string
    {
        return config('laravel-table.icon.show');
    }
}
