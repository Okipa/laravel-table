<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Component;

abstract class AbstractColumnAction
{
    public string $columnActionClass;

    public string $modelClass;

    public string $modelKey;

    public string $attribute;

    public bool $isAllowed = true;

    public string|false|null $confirmationQuestion = null;

    public string|false|null $feedbackMessage = null;

    abstract protected function class(Model $model, string $attribute): array;

    abstract protected function title(Model $model, string $attribute): string;

    abstract protected function icon(Model $model, string $attribute): string;

    abstract protected function label(Model $model, string $attribute): string|null;

    abstract protected function defaultConfirmationQuestion(Model $model, string $attribute): string|null;

    abstract protected function defaultFeedbackMessage(Model $model, string $attribute): string|null;

    /** @return mixed|void */
    abstract public function action(Model $model, string $attribute, Component $livewire);

    public function setup(Model $model, string $attribute): void
    {
        $this->columnActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->attribute = $attribute;
    }

    public static function retrieve(array $columnActions, string|null $modelKey, string $attribute): array|null
    {
        if (! $modelKey) {
            return null;
        }

        return Arr::first($columnActions, static fn (array $columnAction) => $columnAction['modelKey'] === $modelKey
            && $columnAction['attribute'] === $attribute);
    }

    public static function make(array $columnActionArray): self
    {
        $columnActionInstance = app($columnActionArray['columnActionClass'], $columnActionArray);
        $columnActionInstance->columnActionClass = $columnActionArray['columnActionClass'];
        $columnActionInstance->modelClass = $columnActionArray['modelClass'];
        $columnActionInstance->modelKey = $columnActionArray['modelKey'];
        $columnActionInstance->attribute = $columnActionArray['attribute'];
        $columnActionInstance->isAllowed = $columnActionArray['isAllowed'];
        $columnActionInstance->confirmationQuestion = $columnActionArray['confirmationQuestion'];
        $columnActionInstance->feedbackMessage = $columnActionArray['feedbackMessage'];

        return $columnActionInstance;
    }

    public function render(Model $model, string $attribute): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.column-action', [
            'columnAction' => $this,
            'class' => $this->class($model, $attribute),
            'title' => $this->title($model, $attribute),
            'label' => $this->label($model, $attribute),
            'icon' => $this->icon($model, $attribute),
            'shouldBeConfirmed' => (bool) $this->getConfirmationQuestion($model, $attribute),
        ]);
    }

    public function when(bool $condition): self
    {
        $this->isAllowed = $condition;

        return $this;
    }

    public function isAllowed(): bool
    {
        return $this->isAllowed;
    }

    public function confirmationQuestion(string|false $confirmationQuestion): self
    {
        $this->confirmationQuestion = $confirmationQuestion;

        return $this;
    }

    public function getConfirmationQuestion(Model $model, string $attribute): string|null
    {
        return $this->confirmationQuestion ?? $this->defaultConfirmationQuestion($model, $attribute);
    }

    public function feedbackMessage(string|false $feedbackMessage): self
    {
        $this->feedbackMessage = $feedbackMessage;

        return $this;
    }

    public function getFeedbackMessage(Model $model, string $attribute): string|null
    {
        return $this->feedbackMessage ?? $this->defaultFeedbackMessage($model, $attribute);
    }
}
