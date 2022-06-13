<?php

namespace Okipa\LaravelTable\BulkActions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;

class CancelEmailVerificationBulkAction extends AbstractBulkAction
{
    public function __construct(public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'cancel_email_verification';
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('Cancel Email Verification') . ' (' . count($allowedModelKeys) . ')';
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('Are you sure you want to :action the :count selected lines?', [
                'action' => __('cancel email verification of'),
                'count' => count($allowedModelKeys),
            ])
            : __('Are you sure you want to :action the line #:primary?', [
                'action' => __('cancel email verification of'),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow :action and will not be affected by this action.', [
                    'action' => __('email verification cancellation'),
                    'count' => $disallowedLinesCount,
                ])
                : __('The line #:primary does not allow :action and will not be affected by this action.', [
                    'action' => __('email verification cancellation'),
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
                'action' => __('unverified (email)'),
            ])
            : __('The line #:primary has been :action.', [
                'primary' => Arr::first($allowedModelKeys),
                'action' => __('unverified (email)'),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow :action and were not affected by this action.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('email verification cancellation'),
                ])
                : __('The line #:primary does not allow :action and was not affected by this action.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('email verification cancellation'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    public function action(Collection $models, Component $livewire): void
    {
        foreach ($models as $model) {
            $model->forceFill([$this->attribute => null])->save();
        }
    }
}
