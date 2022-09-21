<?php

namespace Okipa\LaravelTable\BulkActions;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Component;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;

class DeactivateBulkAction extends AbstractBulkAction
{
    public function __construct(public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'bulk_action_deactivate';
    }

    protected function label(array $allowedModelKeys): string
    {
        return __('Deactivate') . ' (' . count($allowedModelKeys) . ')';
    }

    protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('Are you sure you want to execute the action :action on the :count selected lines?', [
                'action' => __('Deactivate'),
                'count' => count($allowedModelKeys),
            ])
            : __('Are you sure you want to execute the action :action on the line #:primary?', [
                'action' => __('Deactivate'),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow the action :action and will not be affected.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('Deactivate'),
                ])
                : __('The line #:primary does not allow the action :action and will not be affected.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('Deactivate'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null
    {
        $allowedLinesCount = count($allowedModelKeys);
        $allowedLinesSentence = $allowedLinesCount > 1
            ? __('The action :action has been executed on the :count selected lines.', [
                'action' => __('Deactivate'),
                'count' => count($allowedModelKeys),
            ])
            : __('The action :action has been executed on the line #:primary.', [
                'action' => __('Deactivate'),
                'primary' => Arr::first($allowedModelKeys),
            ]);
        $disallowedLinesCount = count($disallowedModelKeys);
        if ($disallowedLinesCount) {
            $disallowedLinesSentence = ' ';
            $disallowedLinesSentence .= $disallowedLinesCount > 1
                ? __(':count selected lines do not allow the action :action and were not affected.', [
                    'count' => $disallowedLinesCount,
                    'action' => __('Deactivate'),
                ])
                : __('The line #:primary does not allow the action :action and was not affected.', [
                    'primary' => Arr::first($disallowedModelKeys),
                    'action' => __('Deactivate'),
                ]);
        }

        return $allowedLinesSentence . ($disallowedLinesSentence ?? '');
    }

    public function action(Collection $models, Component $livewire): void
    {
        foreach ($models as $model) {
            // Update attribute even if it not in model `$fillable`
            $model->forceFill([$this->attribute => false])->save();
        }
    }
}
