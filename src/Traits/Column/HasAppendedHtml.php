<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasAppendedHtml
{
    protected string|null $appendedHtml = null;

    protected bool $forceAppendedHtmlDisplay;

    public function appendHtml(string $html, bool $forceAppendedHtmlDisplay = false): Column
    {
        $this->appendedHtml = $html;
        $this->forceAppendedHtmlDisplay = $forceAppendedHtmlDisplay;

        return $this;
    }

    public function getAppendedHtml(): string|null
    {
        return $this->appendedHtml;
    }

    public function shouldForceAppendedHtmlDisplay(): bool
    {
        return $this->forceAppendedHtmlDisplay;
    }
}
