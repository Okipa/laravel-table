<?php

namespace Okipa\LaravelTable\Abstracts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Component;

abstract class AbstractRowAction
{
    public string $identifier;

    public string $rowActionClass;

    public string $modelClass;

    public string $modelKey;

    protected string|null $class;

    protected string $icon;

    protected string $title;

    public string|null $confirmationMessage = null;

    public string|null $executedMessage = null;

    protected bool $isAllowed = true;

    abstract protected function identifier(): string;

    abstract protected function class(Model $model): string|null;

    abstract protected function icon(Model $model): string;

    abstract protected function title(Model $model): string;

    abstract protected function shouldBeConfirmed(): bool;

    /** @return mixed|void */
    abstract public function action(Model $model, Component $livewire);

    public function setup(Model $model): void
    {
        $this->rowActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->identifier = $this->identifier();
    }

    public static function retrieve(array $rowActions, string $modelKey): array
    {
        return Arr::where($rowActions, static fn(array $rowAction) => $rowAction['modelKey'] === $modelKey);
    }

    public static function make(array $rowActionArray): self
    {
        $rowActionInstance = app($rowActionArray['rowActionClass'], $rowActionArray);
        $rowActionInstance->rowActionClass = $rowActionArray['rowActionClass'];
        $rowActionInstance->modelClass = $rowActionArray['modelClass'];
        $rowActionInstance->modelKey = $rowActionArray['modelKey'];
        $rowActionInstance->identifier = $rowActionArray['identifier'];
        $rowActionInstance->confirmationMessage = $rowActionArray['confirmationMessage'];
        $rowActionInstance->executedMessage = $rowActionArray['executedMessage'];

        return $rowActionInstance;
    }

    public function render(Model $model): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.row-action', [
            'modelKey' => $this->modelKey,
            'class' => $this->class($model),
            'identifier' => $this->identifier,
            'title' => $this->title($model),
            'icon' => $this->icon($model),
            'shouldBeConfirmed' => $this->shouldBeConfirmed(),
        ]);
    }

    public function when(bool $condition): self
    {
        $this->isAllowed = $condition;

        return $this;
    }

    public function isAllowed(): bool
    {
        return $this->isAllowed;
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
}
