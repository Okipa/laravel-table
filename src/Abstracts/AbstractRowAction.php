<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

abstract class AbstractRowAction
{
    public string $rowActionClass;

    public string $modelClass;

    public string $modelKey;

    protected string|null $class;

    public string $key;

    protected string $title;

    protected string $icon;

    public string $confirmationMessage;

    abstract protected function class(): string|null;

    abstract protected function key(): string;

    abstract protected function title(): string;

    abstract protected function icon(): string;

    /** @return mixed|void */
    abstract public function action(Model $model);

    abstract protected function shouldBeConfirmed(): bool;

    public function setup(Model $model): void
    {
        $this->rowActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->key = $this->key();
        $this->confirmationMessage = $this->confirmationMessage ?? __('Are you sure you want to perform this action?');
    }

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

    public static function make(array $rowAction): self
    {
        /** @var self $instance */
        $instance = app($rowAction['rowActionClass'], $rowAction);
        $instance->rowActionClass = $rowAction['rowActionClass'];
        $instance->modelClass = $rowAction['modelClass'];
        $instance->modelKey = $rowAction['modelKey'];
        $instance->key = $rowAction['key'];
        $instance->confirmationMessage = $rowAction['confirmationMessage'];

        return $instance;
    }
}
