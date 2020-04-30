<?php

namespace Okipa\LaravelTable;

use Okipa\LaravelTable\Traits\Column\AppendsHtml;
use Okipa\LaravelTable\Traits\Column\HasClasses;
use Okipa\LaravelTable\Traits\Column\HasCustomHtml;
use Okipa\LaravelTable\Traits\Column\HasCustomValue;
use Okipa\LaravelTable\Traits\Column\HasDateTimeFormat;
use Okipa\LaravelTable\Traits\Column\HasStringLimit;
use Okipa\LaravelTable\Traits\Column\HasTable;
use Okipa\LaravelTable\Traits\Column\HasTitle;
use Okipa\LaravelTable\Traits\Column\IsButton;
use Okipa\LaravelTable\Traits\Column\IsLink;
use Okipa\LaravelTable\Traits\Column\IsLinkedToDatabase;
use Okipa\LaravelTable\Traits\Column\IsSearchable;
use Okipa\LaravelTable\Traits\Column\IsSortable;
use Okipa\LaravelTable\Traits\Column\PrependsHtml;

class Column
{
    use HasTable;
    use IsLinkedToDatabase;
    use HasClasses;
    use HasTitle;
    use IsButton;
    use IsLink;
    use HasDateTimeFormat;
    use HasStringLimit;
    use PrependsHtml;
    use AppendsHtml;
    use HasCustomValue;
    use HasCustomHtml;
    use IsSortable;
    use IsSearchable;

    public function __construct(Table $table, string $dbField = null)
    {
        $this->initializeTable($table);
        $this->initializeDatabaseLink($table, $dbField);
        $this->initializeTitle();
    }
}
