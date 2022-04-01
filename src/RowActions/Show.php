<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Redirector;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Show extends AbstractRowAction
{
    public function __construct(public string $showUrl)
    {
        //
    }

    protected function identifier(): string
    {
        return 'show';
    }

    protected function class(Model $model): string
    {
        return 'link-info';
    }

    protected function icon(Model $model): string
    {
        return config('laravel-table.icon.show');
    }

    protected function title(Model $model): string
    {
        return __('Show');
    }

    protected function defaultConfirmationQuestion(Model $model): string|null
    {
        return null;
    }

    protected function defaultFeedbackMessage(Model $model): string|null
    {
        return null;
    }

    public function action(Model $model, Component $livewire): Redirector
    {
        return redirect()->to($this->showUrl);
    }
}
