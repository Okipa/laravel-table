<?php

namespace Okipa\LaravelTable\Traits\Result;

use Okipa\LaravelTable\Result;

trait HasTitle
{
    public string|null $title = null;

    public function title(string $title): Result
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string|null
    {
        return $this->title;
    }
}
