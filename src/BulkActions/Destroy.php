<?php

namespace Okipa\LaravelTable\BulkActions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;

class Destroy extends AbstractBulkAction
{
    protected function identifier(): string
    {
        return 'destroy';
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('Destroy') . ' (' . count($allowedModelKeys) . ')';
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('Are you sure you want to destroy the :count selected lines?', ['count' => count($allowedModelKeys)])
            : __('Are you sure you want to destroy the selected line #:key?', ['key' => Arr::first($allowedModelKeys)]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow destruction and will not be affected by this action.', [
                    'count' => $disallowedLinesCount,
                ])
                : __('The line #:key does not allow destruction and will not be affected by this action.', [
                    'key' => Arr::first($disallowedModelKeys),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __(':count selected lines have been destroyed.', ['count' => count($allowedModelKeys)])
            : __('The selected line #:key has been destroyed.', ['key' => Arr::first($allowedModelKeys)]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow destruction and were not affected by this action.', [
                    'count' => $disallowedLinesCount,
                ])
                : __('The line #:key does not allow destruction and was not affected by this action.', [
                    'key' => Arr::first($disallowedModelKeys),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    public function action(Collection $models, Component $livewire): void
    {
        $models->each->delete();
    }
}
