<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Component;

abstract class AbstractBulkAction
{
    public string $bulkActionClass;

    public string $modelClass;

    public string $identifier;

    public array $allowedModelKeys;

    public array $disallowedModelKeys;

    public string|false|null $confirmationQuestion = null;

    public string|false|null $feedbackMessage = null;

    protected bool $isAllowed = true;

    public function setup(Model $model): void
    {
        $this->bulkActionClass = $this::class;
        $this->modelClass = $model::class;
        $this->identifier = $this->identifier();
    }

    abstract protected function identifier(): string;

    abstract protected function label(array $allowedModelKeys): string;

    abstract protected function defaultConfirmationQuestion(
        array $allowedModelKeys,
        array $disallowedModelKeys
    ): null|string;

    abstract protected function defaultFeedbackMessage(
        array $allowedModelKeys,
        array $disallowedModelKeys
    ): null|string;

    /** @return mixed|void */
    abstract public function action(Collection $models, Component $livewire);

    public static function retrieve(array $bulkActions, string $identifier): array
    {
        return Arr::first($bulkActions, static fn (array $bulkAction) => $bulkAction['identifier'] === $identifier);
    }

    public static function make(array $bulkActionArray): self
    {
        $bulkActionInstance = app($bulkActionArray['bulkActionClass'], $bulkActionArray);
        $bulkActionInstance->bulkActionClass = $bulkActionArray['bulkActionClass'];
        $bulkActionInstance->modelClass = $bulkActionArray['modelClass'];
        $bulkActionInstance->identifier = $bulkActionArray['identifier'];
        $bulkActionInstance->allowedModelKeys = $bulkActionArray['allowedModelKeys'];
        $bulkActionInstance->disallowedModelKeys = $bulkActionArray['disallowedModelKeys'];
        $bulkActionInstance->confirmationQuestion = $bulkActionArray['confirmationQuestion'];
        $bulkActionInstance->feedbackMessage = $bulkActionArray['feedbackMessage'];

        return $bulkActionInstance;
    }

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.bulk-action', [
            'bulkAction' => $this,
            'label' => $this->label($this->allowedModelKeys),
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

    public function getConfirmationQuestion(): null|string
    {
        return $this->confirmationQuestion ?? $this->defaultConfirmationQuestion(
            $this->allowedModelKeys,
            $this->disallowedModelKeys
        );
    }

    public function feedbackMessage(string|false $feedbackMessage): self
    {
        $this->feedbackMessage = $feedbackMessage;

        return $this;
    }

    public function getFeedbackMessage(): null|string
    {
        return $this->feedbackMessage ?? $this->defaultFeedbackMessage(
            $this->allowedModelKeys,
            $this->disallowedModelKeys
        );
    }
}
