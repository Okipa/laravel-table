<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Redirector;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Edit extends AbstractRowAction
{
    public function __construct(public string $editUrl)
    {
        //
    }

    protected function identifier(): string
    {
        return 'edit';
    }

    protected function class(): string
    {
        return 'link-primary';
    }

    protected function icon(): string
    {
        return config('laravel-table.icon.edit');
    }

    protected function title(): string
    {
        return __('Edit');
    }

    protected function shouldBeConfirmed(): bool
    {
        return false;
    }

    public function action(Model $model, Component $livewire): Redirector
    {
        return redirect()->to($this->editUrl);
    }
}
