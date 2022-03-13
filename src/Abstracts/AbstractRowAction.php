<?php

namespace Okipa\LaravelTable\Abstracts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Livewire\Component;

abstract class AbstractRowAction
{
    public string $rowActionClass;

    public string $modelClass;

    public string $modelKey;

    public string $key;

    public string|null $confirmMessage = null;

    public Closure|null $hookClosure = null;

    protected string|null $class;

    protected string $title;

    protected string $icon;

    protected Closure|null $whenClosure = null;

    public static function make(array $rowAction): self
    {
        /** @var self $instance */
        $instance = app($rowAction['rowActionClass'], $rowAction);
        $instance->rowActionClass = $rowAction['rowActionClass'];
        $instance->modelClass = $rowAction['modelClass'];
        $instance->modelKey = $rowAction['modelKey'];
        $instance->key = $rowAction['key'];
        $instance->confirmMessage = $rowAction['confirmMessage'];
        $instance->hookClosure = $rowAction['hookClosure'];

        return $instance;
    }

    public static function getFromModelKey(array $rowActions, string $modelKey): array
    {
        return Arr::where($rowActions, static fn(array $rowAction) => $rowAction['modelKey'] === $modelKey);
    }

    /** @return mixed|void */
    abstract public function action(Model $model);

    public function when(Closure $whenClosure): self
    {
        $this->whenClosure = $whenClosure;

        return $this;
    }

    public function isAllowed(Model $model): bool
    {
        if (! $this->whenClosure) {
            return true;
        }

        return ($this->whenClosure)($model);
    }

    public function hook(Closure $hookClosure): self
    {
        $this->hookClosure = $hookClosure;

        return $this;
    }

    public function executeHook(Component $livewire, Model $model): void
    {
        if (! $this->hookClosure) {
            return;
        }
        ($this->hookClosure)($livewire, $model);
    }

    public function setup(Model $model): void
    {
        $this->rowActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->key = $this->key();
        $this->confirmMessage = $this->confirmMessage ?: __('Are you sure you want to perform this action?');
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
