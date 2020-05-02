<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasDateTimeFormat
{
    protected ?string $dateTimeFormat = null;

    /**
     * Set the format for a datetime, date or time database column (optional).
     * Carbon::parse($value)->format($format) method is used under the hood.
     *
     * @param string $dateTimeFormat
     *
     * @return \Okipa\LaravelTable\Column
     */
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
