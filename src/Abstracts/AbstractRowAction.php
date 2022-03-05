<?php

namespace Okipa\LaravelTable\Abstracts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

abstract class AbstractRowAction extends Component
{
    abstract public function action(Model $model, string $key): void;

    abstract public function render(): View|Closure|string;
}
