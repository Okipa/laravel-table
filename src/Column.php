<?php

namespace Okipa\LaravelTable;

use Okipa\LaravelTable\Traits\Column\HasAppendedHtml;
use Okipa\LaravelTable\Traits\Column\HasClasses;
use Okipa\LaravelTable\Traits\Column\HasCustomHtml;
use Okipa\LaravelTable\Traits\Column\HasCustomValue;
use Okipa\LaravelTable\Traits\Column\HasDateTimeFormat;
use Okipa\LaravelTable\Traits\Column\HasStringLimit;
use Okipa\LaravelTable\Traits\Column\HasTable;
use Okipa\LaravelTable\Traits\Column\HasTitle;
use Okipa\LaravelTable\Traits\Column\IsButton;
use Okipa\LaravelTable\Traits\Column\IsLink;
use Okipa\LaravelTable\Traits\Column\IsLinkedToDataSource;
use Okipa\LaravelTable\Traits\Column\IsSearchable;
use Okipa\LaravelTable\Traits\Column\IsSortable;
use Okipa\LaravelTable\Traits\Column\HasPrependedHtml;

class Column
{
    use HasTable;
    use IsLinkedToDataSource;
    use HasClasses;
    use HasTitle;
    use IsButton;
    use IsLink;
    use HasDateTimeFormat;
    use HasStringLimit;
    use HasPrependedHtml;
    use HasAppendedHtml;
    use HasCustomValue;
    use HasCustomHtml;
    use IsSortable;
    use IsSearchable;

    public function __construct(Table $table, string $dataSourceField = null)
    {
        $this->initializeTable($table);
        $this->initializeDataSourceLink($table, $dataSourceField);
        $this->initializeTitle();
    }
}
