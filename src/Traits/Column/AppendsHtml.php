<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait AppendsHtml
{
    protected ?string $appendedHtml = null;

    protected bool $forceAppendedHtmlDisplay;

    /**
     * Append HTML to the displayed value.
     * Set the second param as true if you want the appended HTML to be displayed even if the column has no value.
     *
     * @param string $appendedHtml
     * @param bool $forceAppendedHtmlDisplay
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function appendsHtml(string $appendedHtml, bool $forceAppendedHtmlDisplay = false): Column
    {
        $this->appendedHtml = $appendedHtml;
        $this->forceAppendedHtmlDisplay = $forceAppendedHtmlDisplay;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getAppendedHtml(): ?string
    {
        return $this->appendedHtml;
    }

    public function shouldForceAppendedHtmlDisplay(): bool
    {
        return $this->forceAppendedHtmlDisplay;
    }
}
