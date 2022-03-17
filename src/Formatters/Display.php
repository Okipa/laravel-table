<?php

namespace Okipa\LaravelTable\Formatters;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class Display extends AbstractFormatter
{
    public function __construct(protected Closure $displayClosure)
    {
        //
    }

    public function format(Model $model, string $key): string|null
    {
        $url = ($this->displayClosure)($model);
        if (! $url) {
            return null;
        }
        $display = __('Display');

        return <<<BLADE
        <a class="btn-outline-primary btn-sm" href="$url" target="_blank">
           <i class="fa-solid fa-up-right-from-square"></i>
           $display
        </a>
        BLADE;
    }
}
