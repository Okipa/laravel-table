<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait AppendsHtml
{
    protected ?string $appendedHtml = null;

    protected bool $forceAppendedHtmlDisplay;

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
