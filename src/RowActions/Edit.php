<?php

namespace Okipa\LaravelTable\RowActions;

use Livewire\Redirector;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Edit extends AbstractRowAction
{
    public function __construct(protected string $editUrl)
    {
        //
    }

    protected function key(): string
    {
        return 'edit';
    }

    protected function title(): string
    {
        return __('Edit');
    }

    protected function icon(): string
    {
        return config('laravel-table.icon.edit');
    }

    protected function shouldBeConfirmed(): bool
    {
        return false;
    }

    public function action(): Redirector
    {
        return redirect()->to($this->editUrl);
    }
}
