<?php

namespace Okipa\LaravelTable\BulkActions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;

class ActivateBulkAction extends AbstractBulkAction
{
    public function __construct(public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'activate';
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('Activate') . ' (' . count($allowedModelKeys) . ')';
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('Are you sure you want to :action the :count selected lines?', [
                'action' => Str::lower(__('Activate')),
                'count' => count($allowedModelKeys),
            ])
            : __('Are you sure you want to :action the line #:primary?', [
                'action' => Str::lower(__('Activate')),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow :action and will not be affected by this action.', [
                    'action' => __('activation'),
                    'count' => $disallowedLinesCount,
                ])
                : __('The line #:primary does not allow :action and will not be affected by this action.', [
                    'action' => __('activation'),
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
                'action' => __('activated'),
            ])
            : __('The line #:primary has been :action.', [
                'primary' => Arr::first($allowedModelKeys),
                'action' => __('activated'),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow :action and were not affected by this action.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('activation'),
                ])
                : __('The line #:primary does not allow :action and was not affected by this action.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('activation'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    public function action(Collection $models, Component $livewire): void
    {
        foreach ($models as $model) {
            $model->forceFill([$this->attribute => true])->save();
        }
    }
}
