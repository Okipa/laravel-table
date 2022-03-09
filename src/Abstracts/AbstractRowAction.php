<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

abstract class AbstractRowAction
{
    public string $modelClass;

    public string $modelKey;

    protected string|null $class;

    public string $key;

    protected string $title;

    protected string $icon;

    protected bool $shouldBeConfirmed;

    public string $confirmationMessage;

    abstract protected function class(): string|null;

    abstract protected function key(): string;

    abstract protected function title(): string;

    abstract protected function icon(): string;

    /** @return mixed|void */
    abstract public function action(Model $model);

    abstract protected function shouldBeConfirmed(): bool;

    public function render(Model $model): View
    {
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->class = $this->class();
        $this->key = $this->key();
        $this->title = $this->title();
        $this->icon = $this->icon();
        $this->shouldBeConfirmed = $this->shouldBeConfirmed();
        $this->confirmationMessage = $this->confirmationMessage ?? __('Are you sure you want to perform this action?');

        return view('laravel-table::' . Config::get('laravel-table.ui') . '.row-action', [
            'model' => $model,
            'class' => $this->class(),
            'key' => $this->key,
            'title' => $this->title,
            'icon' => $this->icon,
            'shouldBeConfirmed' => $this->shouldBeConfirmed,
        ]);
    }
}
