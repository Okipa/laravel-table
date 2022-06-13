<?php

namespace Okipa\LaravelTable\BulkActions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;

class DestroyBulkAction extends AbstractBulkAction
{
    public function action(Collection $models, Component $livewire): void
    {
        $models->each->delete();
    }

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
            ? __('Are you sure you want to :action the :count selected lines?', [
                'action' => Str::lower(__('Destroy')),
                'count' => count($allowedModelKeys),
            ])
            : __('Are you sure you want to :action the line #:primary?', [
                'action' => Str::lower(__('Destroy')),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow :action and will not be affected by this action.', [
                    'action' => __('destruction'),
                    'count' => $disallowedLinesCount,
                ])
                : __('The line #:primary does not allow :action and will not be affected by this action.', [
                    'action' => __('destruction'),
                    'primary' => Arr::first($disallowedModelKeys),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __(':count selected lines have been :action.', [
                'count' => count($allowedModelKeys),
                'action' => __('destroyed'),
            ])
            : __('The line #:primary has been :action.', [
                'primary' => Arr::first($allowedModelKeys),
                'action' => __('destroyed'),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow :action and were not affected by this action.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('destruction'),
                ])
                : __('The line #:primary does not allow :action and was not affected by this action.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('destruction'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }
}
