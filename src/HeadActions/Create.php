<?php

namespace Okipa\LaravelTable\HeadActions;

use Livewire\Component;
use Livewire\Redirector;
use Okipa\LaravelTable\Abstracts\AbstractHeadAction;

class Create extends AbstractHeadAction
{
    public function __construct(public string $createUrl)
    {
        //
    }

    protected function class(): string
    {
        return 'btn btn-success';
    }

    protected function title(): string
    {
        return __('Create');
    }

    protected function icon(): string
    {
        return config('laravel-table.icon.create');
    }

    public function action(Component $livewire): Redirector
    {
        return redirect()->to($this->createUrl);
    }
}
