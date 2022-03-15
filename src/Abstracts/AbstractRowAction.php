<?php

namespace Okipa\LaravelTable\Abstracts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Component;

abstract class AbstractRowAction
{
    public string $rowActionClass;

    public string $modelClass;

    public string $modelKey;

    public string $key;

    public string|null $confirmationMessage = null;

    public string|null $executedMessage = null;

    protected string|null $class;

    protected string $title;

    protected string $icon;

    protected Closure|null $allowWhenClosure = null;

    public static function make(array $rowActionArray): self
    {
        $rowActionInstance = app($rowActionArray['rowActionClass'], $rowActionArray);
        $rowActionInstance->rowActionClass = $rowActionArray['rowActionClass'];
        $rowActionInstance->modelClass = $rowActionArray['modelClass'];
        $rowActionInstance->modelKey = $rowActionArray['modelKey'];
        $rowActionInstance->key = $rowActionArray['key'];
        $rowActionInstance->confirmationMessage = $rowActionArray['confirmationMessage'];
        $rowActionInstance->executedMessage = $rowActionArray['executedMessage'];

        return $rowActionInstance;
    }

    public static function getFromModelKey(array $rowActions, string $modelKey): array
    {
        return Arr::where($rowActions, static fn(array $rowAction) => $rowAction['modelKey'] === $modelKey);
    }

    /** @return mixed|void */
    abstract public function action(Model $model, Component $livewire);

    public function onlyWhen(Closure $allowWhenClosure): self
    {
        $this->allowWhenClosure = $allowWhenClosure;

        return $this;
    }

    public function isAllowed(Model $model): bool
    {
        if (! $this->allowWhenClosure) {
            return true;
        }

        return ($this->allowWhenClosure)($model);
    }

    public function confirmationMessage(string $confirmationMessage): self
    {
        $this->confirmationMessage = $confirmationMessage;

        return $this;
    }

    public function getConfirmationMessage(): string
    {
        return $this->confirmationMessage ?: __('Are you sure you want to perform this action?');
    }

    public function executedMessage(string $executedMessage): self
    {
        $this->executedMessage = $executedMessage;

        return $this;
    }

    public function getExecutedMessage(): string
    {
        return $this->executedMessage ?: __('Action has been executed.');
    }

    public function setup(Model $model): void
    {
        $this->rowActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->key = $this->key();
    }

    abstract protected function key(): string;

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.row-action', [
            'modelKey' => $this->modelKey,
            'class' => $this->class(),
            'key' => $this->key,
            'title' => $this->title(),
            'icon' => $this->icon(),
            'shouldBeConfirmed' => $this->shouldBeConfirmed(),
        ]);
    }

    abstract protected function class(): string|null;

    abstract protected function title(): string;

    abstract protected function icon(): string;

    abstract protected function shouldBeConfirmed(): bool;
}
