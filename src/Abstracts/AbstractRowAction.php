<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

abstract class AbstractRowAction
{
    public string $key;

    protected string $title;

    protected string $icon;

    protected bool $shouldBeConfirmed;

    public string $confirmationMessage;

    protected Model $model;

    abstract protected function key(): string;

    abstract protected function title(): string;

    abstract protected function icon(): string;

    /** @return mixed|void */
    abstract protected function action();

    abstract protected function shouldBeConfirmed(): bool;

    public function render(Model $model): View
    {
        $this->key = $this->key();
        $this->title = $this->title();
        $this->icon = $this->icon();
        $this->shouldBeConfirmed = $this->shouldBeConfirmed();
        $this->confirmationMessage = $this->confirmationMessage ?? __('Are you sure you want to perform this action?');
        $this->model = $model;

        return view('laravel-table::' . Config::get('laravel-table.ui') . '.row-action', [
            'key' => $this->key,
            'title' => $this->title,
            'icon' => $this->icon,
            'shouldBeConfirmed' => $this->shouldBeConfirmed,
            'model' => $model,
        ]);
    }
}
