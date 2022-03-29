<?php

namespace Okipa\LaravelTable\ColumnActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractColumnAction;

class Toggle extends AbstractColumnAction
{
    protected function class(Model $model, string $attribute): string|null
    {
        return $model->{$attribute} ? 'link-danger p-1' : 'link-success p-1';
    }

    protected function icon(Model $model, string $attribute): string
    {
        return $model->{$attribute}
            ? config('laravel-table.icon.inactive')
            : config('laravel-table.icon.active');
    }

    protected function title(Model $model, string $attribute): string
    {
        return __('Toggle');
    }

    protected function shouldBeConfirmed(): bool
    {
        return false;
    }

    public function action(Model $model, string $attribute, Component $livewire)
    {
        return $model->update([$attribute => ! $model->{$attribute}]);
    }
}
