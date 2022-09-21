<?php

namespace Okipa\LaravelTable\Formatters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class StrLimitFormatter extends AbstractFormatter
{
    public function __construct(protected int $limit = 100, protected string $end = '...')
    {
        //
    }

    public function format(Model $model, string $attribute): string|null
    {
        $string = $model->{$attribute};
        if (! $string) {
            return null;
        }
        $truncatedString = Str::limit($model->{$attribute}, $this->limit, $this->end);

        return <<<HTML
        <span title="$string" data-bs-toggle="tooltip">$truncatedString</span>
        HTML;
    }
}
