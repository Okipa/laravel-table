<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Livewire\Redirector;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class ShowRowAction extends AbstractRowAction
{
    public function __construct(public string $showUrl)
    {
        //
    }

    protected function identifier(): string
    {
        return 'row_action_show';
    }

    protected function class(Model $model): array
    {
        return ['link-info'];
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

    public function action(Model $model, Component $livewire): RedirectResponse|Redirector
    {
        return redirect()->to($this->showUrl);
    }
}
