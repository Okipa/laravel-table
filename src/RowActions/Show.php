<?php

namespace Okipa\LaravelTable\RowActions;

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
        return 'btn-info';
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

    public function action(): Redirector
    {
        return redirect()->to($this->showUrl);
    }
}
