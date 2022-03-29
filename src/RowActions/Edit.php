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

    protected function class(Model $model): string
    {
        return 'link-primary';
    }

    protected function icon(Model $model): string
    {
        return config('laravel-table.icon.edit');
    }

    protected function title(Model $model): string
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
