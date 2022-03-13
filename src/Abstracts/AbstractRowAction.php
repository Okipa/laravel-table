<?php

namespace Okipa\LaravelTable\Abstracts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

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

    public static function make(array $rowAction): self
    {
        /** @var self $instance */
        $instance = app($rowAction['rowActionClass'], $rowAction);
        $instance->rowActionClass = $rowAction['rowActionClass'];
        $instance->modelClass = $rowAction['modelClass'];
        $instance->modelKey = $rowAction['modelKey'];
        $instance->key = $rowAction['key'];
        $instance->confirmationMessage = $rowAction['confirmationMessage'];
        $instance->executedMessage = $rowAction['executedMessage'];

        return $instance;
    }

    public static function getFromModelKey(array $rowActions, string $modelKey): array
    {
        return Arr::where($rowActions, static fn(array $rowAction) => $rowAction['modelKey'] === $modelKey);
    }

    /** @return mixed|void */
    abstract public function action(Model $model);

    public function allowWhen(Closure $allowWhenClosure): self
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
        return view('laravel-table::' . Config::get('laravel-table.ui') . '.row-action', [
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
