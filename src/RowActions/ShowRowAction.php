<?php

namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class ShowRowAction extends AbstractRowAction
{
    protected RedirectRowAction $redirectRowAction;

    public function __construct(public string $showUrl, public bool $openInNewWindow = false)
    {
        $this->redirectRowAction = new RedirectRowAction(
            url: $showUrl,
            title: __('Show'),
            icon: config('laravel-table.icon.show'),
            openInNewWindow: $openInNewWindow
        );
    }

    protected function identifier(): string
    {
        return $this->redirectRowAction->identifier();
    }

    protected function class(Model $model): array
    {
        return $this->redirectRowAction->class($model);
    }

    protected function icon(Model $model): string
    {
        return $this->redirectRowAction->icon($model);
    }

    protected function title(Model $model): string
    {
        return $this->redirectRowAction->title($model);
    }

    protected function defaultConfirmationQuestion(Model $model): string|null
    {
        return $this->redirectRowAction->defaultConfirmationQuestion($model);
    }

    protected function defaultFeedbackMessage(Model $model): string|null
    {
        return $this->redirectRowAction->defaultFeedbackMessage($model);
    }

    public function action(Model $model, Component $livewire): void
    {
        $this->redirectRowAction->action($model, $livewire);
    }
}
