<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Redirector;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Show extends AbstractRowAction
{
    public function __construct(protected string $showUrl)
    {
        //
    }

    protected function class(): string
    {
        return 'text-info';
    }

    protected function key(): string
    {
        return 'show';
    }

    protected function title(): string
    {
        return __('Show');
    }

    protected function icon(): string
    {
        return config('laravel-table.icon.show');
    }

    protected function shouldBeConfirmed(): bool
    {
        return false;
    }

    public function action(Model $model): Redirector
    {
        return redirect()->to($this->showUrl);
    }
}
