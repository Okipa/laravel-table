<?php

namespace Okipa\LaravelTable\Abstracts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Component;

abstract class AbstractColumnAction
{
    public string $columnActionClass;

    public string $modelClass;

    public string $modelKey;

    public string $attribute;

    public string|null $confirmationMessage = null;

    public string|null $executedMessage = null;

    protected string|null $class;

    protected string $icon;

    protected string $title;

    protected Closure|null $allowWhenClosure = null;

    abstract protected function class(Model $model, string $attribute): string|null;

    abstract protected function icon(Model $model, string $attribute): string;

    abstract protected function title(Model $model, string $attribute): string;

    abstract protected function shouldBeConfirmed(): bool;

    /** @return mixed|void */
    abstract public function action(Model $model, string $attribute, Component $livewire);

    public function setup(Model $model, string $attribute): void
    {
        $this->columnActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->attribute = $attribute;
    }

    public static function retrieve(array $columnActions, string $modelKey, string $attribute): array|null
    {
        return Arr::first($columnActions, static fn(array $columnAction) => $columnAction['modelKey'] === $modelKey
            && $columnAction['attribute'] === $attribute);
    }

    public static function make(array $columnActionArray): self
    {
        $columnActionInstance = app($columnActionArray['columnActionClass'], $columnActionArray);
        $columnActionInstance->columnActionClass = $columnActionArray['columnActionClass'];
        $columnActionInstance->modelClass = $columnActionArray['modelClass'];
        $columnActionInstance->modelKey = $columnActionArray['modelKey'];
        $columnActionInstance->attribute = $columnActionArray['attribute'];
        $columnActionInstance->confirmationMessage = $columnActionArray['confirmationMessage'];
        $columnActionInstance->executedMessage = $columnActionArray['executedMessage'];

        return $columnActionInstance;
    }


    public function render(Model $model, string $attribute): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.cell-action', [
            'modelKey' => $this->modelKey,
            'attribute' => $this->attribute,
            'class' => $this->class($model, $attribute),
            'title' => $this->title($model, $attribute),
            'icon' => $this->icon($model, $attribute),
            'shouldBeConfirmed' => $this->shouldBeConfirmed(),
        ]);
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
