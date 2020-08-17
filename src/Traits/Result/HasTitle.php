<?php

namespace Okipa\LaravelTable\Traits\Result;

use Okipa\LaravelTable\Result;

trait HasTitle
{
    public ?string $title = null;

    public function title(string $title): Result
    {
        $this->title = $title;

        /** @var \Okipa\LaravelTable\Result $this */
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
