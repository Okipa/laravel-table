<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

abstract class AbstractRowAction
{
    public string|null $key = null;

    public string|null $title = null;

    public string $icon;

    protected Model $model;

    public function render(Model $model): View
    {
        $rowActionClassName = (new \ReflectionClass($this))->getShortName();
        $this->key = $this->key ?: Str::snake($rowActionClassName);
        $this->title = $this->title ?: Str::headline($rowActionClassName);
        $this->icon = $this->icon();
        $this->model = $model;

        return view('laravel-table::' . Config::get('laravel-table.ui') . '.row-action', [
            'key' => $this->key,
            'title' => $this->title,
            'icon' => $this->icon,
        ]);
    }

    abstract protected function icon(): string;

    abstract protected function action(): mixed;
}
