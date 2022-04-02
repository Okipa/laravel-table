<?php

namespace Okipa\LaravelTable\ColumnActions;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractColumnAction;

class ToggleBoolean extends AbstractColumnAction
{
    protected function class(Model $model, string $attribute): string|null
    {
        return $model->{$attribute} ? 'link-success p-1' : 'link-danger p-1';
    }

    protected function title(Model $model, string $attribute): string
    {
        return $model->{$attribute} ? __('Toggle off') : __('Toggle on');
    }

    protected function icon(Model $model, string $attribute): string
    {
        return $model->{$attribute}
            ? config('laravel-table.icon.toggle_on')
            : config('laravel-table.icon.toggle_off');
    }

    protected function label(Model $model, string $attribute): string|null
    {
        return null;
    }

    protected function defaultConfirmationQuestion(Model $model, string $attribute): string|null
    {
        return null;
    }

    protected function defaultFeedbackMessage(Model $model, string $attribute): string|null
    {
        return __('The field :attribute from the line #:primary has been :action.', [
            'attribute' => __('validation.attributes.' . $attribute),
            'primary' => $model->getKey(),
            'action' => $model->{$attribute} ? __('toggled off') : __('toggled on'),
        ]);
    }

    public function action(Model $model, string $attribute, Component $livewire)
    {
        return $model->update([$attribute => ! $model->{$attribute}]);
    }
}
