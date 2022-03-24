<?php

namespace Okipa\LaravelTable\Abstracts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Component;

abstract class AbstractCellAction
{
    public string $identifier;

    public string $rowActionClass;

    public string $modelClass;

    public string $modelKey;

    protected string|null $class;

    protected string $title;

    protected string $icon;

    public string|null $confirmationMessage = null;

    public string|null $executedMessage = null;

    protected Closure|null $allowWhenClosure = null;

    abstract protected function identifier(): string;

    abstract protected function class(Model $model, string $attribute): string|null;

    abstract protected function title(Model $model, string $attribute): string;

    abstract protected function icon(Model $model, string $attribute): string;

    abstract protected function shouldBeConfirmed(): bool;

    /** @return mixed|void */
    abstract public function action(Model $model, string $attribute, Component $livewire);

    public static function make(array $rowActionArray): self
    {
        $rowActionInstance = app($rowActionArray['rowActionClass'], $rowActionArray);
        $rowActionInstance->identifier = $rowActionArray['identifier'];
        $rowActionInstance->rowActionClass = $rowActionArray['rowActionClass'];
        $rowActionInstance->modelClass = $rowActionArray['modelClass'];
        $rowActionInstance->modelKey = $rowActionArray['modelKey'];
        $rowActionInstance->confirmationMessage = $rowActionArray['confirmationMessage'];
        $rowActionInstance->executedMessage = $rowActionArray['executedMessage'];

        return $rowActionInstance;
    }

    public function render(Model $model, string $attribute): View
    {
        $this->identifier = $this->identifier();
        $this->rowActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();

        return view('laravel-table::' . config('laravel-table.ui') . '.cell-action', [
            'modelKey' => $this->modelKey,
            'class' => $this->class($model, $attribute),
            'identifier' => $this->identifier,
            'title' => $this->title($model, $attribute),
            'icon' => $this->icon($model, $attribute),
            'shouldBeConfirmed' => $this->shouldBeConfirmed(),
        ]);
    }

    public static function getFromModelKey(array $rowActions, string $modelKey): array
    {
        return Arr::where($rowActions, static fn(array $rowAction) => $rowAction['modelKey'] === $modelKey);
    }

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
}
