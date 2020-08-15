<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasDateTimeFormat
{
    protected ?string $dateTimeFormat = null;

    public function dateTimeFormat(string $dateTimeFormat): Column
    {
        $this->dateTimeFormat = $dateTimeFormat;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getDateTimeFormat(): ?string
    {
        return $this->dateTimeFormat;
    }
}
