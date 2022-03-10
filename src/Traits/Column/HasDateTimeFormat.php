<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasDateTimeFormat
{
    protected ?string $dateTimeFormat = null;

    protected ?string $timezone = null;

    public function dateTimeFormat(string $dateTimeFormat, string $timezone = null): Column
    {
        $this->dateTimeFormat = $dateTimeFormat;
        $this->timezone = $timezone;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getDateTimeFormat(): ?string
    {
        return $this->dateTimeFormat;
    }

    public function getTimezone(): string
    {
        return $this->timezone ?: config('app.timezone');
    }
}
