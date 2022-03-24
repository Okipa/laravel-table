<?php

namespace Okipa\LaravelTable\CellActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractCellAction;

class Toggle extends AbstractCellAction
{
    protected function identifier(): string
    {
        return 'toggle';
    }

    protected function class(Model $model, string $attribute): string|null
    {
        return $model->{$attribute} ? 'link-danger' : 'link-success';
    }

    protected function title(Model $model, string $attribute): string
    {
        return __('Toggle');
    }

    protected function icon(Model $model, string $attribute): string
    {
        return $model->{$attribute}
            ? config('laravel-table.icon.inactive')
            : config('laravel-table.icon.active');
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
