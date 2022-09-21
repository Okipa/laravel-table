<?php

namespace Okipa\LaravelTable\BulkActions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;

class VerifyEmailBulkAction extends AbstractBulkAction
{
    public function __construct(public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'bulk_action_verify_email';
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('Verify Email') . ' (' . count($allowedModelKeys) . ')';
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('Are you sure you want to execute the action :action on the :count selected lines?', [
                'action' => __('Verify Email'),
                'count' => count($allowedModelKeys),
            ])
            : __('Are you sure you want to execute the action :action on the line #:primary?', [
                'action' => __('Verify Email'),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow the action :action and will not be affected.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('Verify Email'),
                ])
                : __('The line #:primary does not allow the action :action and will not be affected.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('Verify Email'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('The action :action has been executed on the :count selected lines.', [
                'action' => __('Verify Email'),
                'count' => count($allowedModelKeys),
            ])
            : __('The action :action has been executed on the line #:primary.', [
                'action' => __('Verify Email'),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow the action :action and were not affected.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('Verify Email'),
                ])
                : __('The line #:primary does not allow the action :action and was not affected.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('Verify Email'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    public function action(Collection $models, Component $livewire): void
    {
        foreach ($models as $model) {
            // Update attribute even if it not in model `$fillable`
            $model->forceFill([$this->attribute => Date::now()])->save();
        }
    }
}
