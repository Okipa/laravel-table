<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasDateTimeFormat
{
    protected string|null $dateTimeFormat = null;

    public function dateTimeFormat(string $dateTimeFormat): Column
    {
        $this->dateTimeFormat = $dateTimeFormat;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getDateTimeFormat(): string|null
    {
        return $this->dateTimeFormat;
    }
}
