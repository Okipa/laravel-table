<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Component;

abstract class AbstractRowAction
{
    public string $rowActionClass;

    public string $modelClass;

    public string $modelKey;

    public string $identifier;

    public string|false|null $confirmationQuestion = null;

    public string|false|null $feedbackMessage = null;

    protected array $class;

    protected string $icon;

    protected string $title;

    protected bool $isAllowed = true;

    public function setup(Model $model): void
    {
        $this->rowActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->modelKey = $model->getKey();
        $this->identifier = $this->identifier();
    }

    abstract protected function identifier(): string;

    abstract protected function class(Model $model): array;

    abstract protected function icon(Model $model): string;

    abstract protected function title(Model $model): string;

    abstract protected function defaultConfirmationQuestion(Model $model): null|string;

    abstract protected function defaultFeedbackMessage(Model $model): null|string;

    /** @return mixed|void */
    abstract public function action(Model $model, Component $livewire);

    public static function retrieve(array $rowActions, null|string $modelKey): array
    {
        if (! $modelKey) {
            return [];
        }

        return Arr::where($rowActions, static fn (array $rowAction) => $rowAction['modelKey'] === $modelKey);
    }

    public static function make(array $rowActionArray): self
    {
        $rowActionInstance = app($rowActionArray['rowActionClass'], $rowActionArray);
        $rowActionInstance->rowActionClass = $rowActionArray['rowActionClass'];
        $rowActionInstance->modelClass = $rowActionArray['modelClass'];
        $rowActionInstance->modelKey = $rowActionArray['modelKey'];
        $rowActionInstance->identifier = $rowActionArray['identifier'];
        $rowActionInstance->confirmationQuestion = $rowActionArray['confirmationQuestion'];
        $rowActionInstance->feedbackMessage = $rowActionArray['feedbackMessage'];

        return $rowActionInstance;
    }

    public function render(Model $model): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.row-action', [
            'rowAction' => $this,
            'class' => $this->class($model),
            'title' => $this->title($model),
            'icon' => $this->icon($model),
            'shouldBeConfirmed' => (bool) $this->getConfirmationQuestion($model),
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

    public function getConfirmationQuestion(Model $model): null|string
    {
        return $this->confirmationQuestion ?? $this->defaultConfirmationQuestion($model);
    }

    public function feedbackMessage(string|false $feedbackMessage): self
    {
        $this->feedbackMessage = $feedbackMessage;

        return $this;
    }

    public function getFeedbackMessage(Model $model): null|string
    {
        return $this->feedbackMessage ?? $this->defaultFeedbackMessage($model);
    }
}
