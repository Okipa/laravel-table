<?php

namespace Okipa\LaravelTable;

use Okipa\LaravelTable\Traits\Result\HasClasses;
use Okipa\LaravelTable\Traits\Result\HasCustomHtml;
use Okipa\LaravelTable\Traits\Result\HasTitle;

class Result
{
    use HasClasses;
    use HasTitle;
    use HasCustomHtml;

    public function __construct()
    {
        $this->initializeClasses();
    }
}
