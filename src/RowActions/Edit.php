<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Edit extends AbstractRowAction
{
    public function __construct(protected string $editRouteName)
    {
        //
    }

    protected function icon(): string
    {
        return config('laravel-table.icon.edit');
    }

    public function action(): mixed
    {
        return redirect()->route($this->editRouteName, $this->model);
    }
}
