<?php

namespace Okipa\LaravelTable\ColumnActions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractColumnAction;

class ToggleEmailVerifiedColumnAction extends AbstractColumnAction
{
    protected function class(Model $model, string $attribute): array
    {
        return [
            'link-success' => $model->{$attribute},
            'link-danger' => ! $model->{$attribute},
        ];
    }

    protected function icon(Model $model, string $attribute): string
    {
        return $model->{$attribute}
            ? config('laravel-table.icon.email_verified')
            : config('laravel-table.icon.email_unverified');
    }

    protected function title(Model $model, string $attribute): string
    {
        return $model->{$attribute} ? __('Unverify Email') : __('Verify Email');
    }

    protected function label(Model $model, string $attribute): string|null
    {
        return null;
    }

    protected function defaultConfirmationQuestion(Model $model, string $attribute): string|null
    {
        return __('Are you sure you want to execute the action :action on the field '
            . ':attribute from the line #:primary?', [
                'action' => $model->{$attribute} ? __('Unverify Email') : __('Verify Email'),
                'attribute' => __('validation.attributes.' . $attribute),
                'primary' => $model->getKey(),
            ]);
    }

    protected function defaultFeedbackMessage(Model $model, string $attribute): string|null
    {
        return __('The action :action has been executed on the field :attribute from the line #:primary.', [
            'action' => $model->{$attribute} ? __('Unverify Email') : __('Verify Email'),
            'attribute' => __('validation.attributes.' . $attribute),
            'primary' => $model->getKey(),
        ]);
    }

    public function action(Model $model, string $attribute, Component $livewire): void
    {
        // Update attribute even if it not in model `$fillable`
        $model->forceFill([$attribute => $model->{$attribute} ? null : Date::now()])->save();
    }
}
