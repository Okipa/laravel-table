<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Edit extends AbstractRowAction
{
    public function __construct(protected string $route)
    {
        //
    }

    public function action(Model $model, string $key): void
    {
        // TODO: Implement rowAction() method.
    }

    public function render(): View
    {
        return view('laravel-table::' . Config::get('laravel-table.ui') . '.row-actions.edit');
    }
}
