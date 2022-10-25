<?php

namespace Okipa\LaravelTable\HeadActions;

use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractHeadAction;

class CreateHeadAction extends AbstractHeadAction
{
    protected RedirectHeadAction $redirectHeadAction;

    public function __construct(public string $createUrl, bool $openInNewWindow = false)
    {
        $this->redirectHeadAction = new RedirectHeadAction(
            url: $createUrl,
            label: __('Create'),
            icon: config('laravel-table.icon.create'),
            openInNewWindow: $openInNewWindow
        );
    }

    protected function class(): array
    {
        return $this->redirectHeadAction->class();
    }

    protected function title(): string
    {
        return $this->redirectHeadAction->title();
    }

    protected function icon(): string
    {
        return $this->redirectHeadAction->icon();
    }

    public function action(Component $livewire): void
    {
        $this->redirectHeadAction->action($livewire);
    }
}
