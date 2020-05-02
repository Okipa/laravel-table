<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasStringLimit
{
    protected ?int $stringLimit = null;

    /**
     * Set the string value display limitation (optional).
     * Shows "..." when the limit is reached.
     *
     * @param int $stringLimit
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function stringLimit(int $stringLimit): Column
    {
        $this->stringLimit = $stringLimit;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getStringLimit(): ?int
    {
        return $this->stringLimit;
    }
}
