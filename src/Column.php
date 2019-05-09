<?php

namespace Okipa\LaravelTable;

use Closure;
use ErrorException;
use InvalidArgumentException;

class Column
{
    /**
     * The table instance the table column is associated to.
     *
     * @property \Okipa\LaravelTable\Table $table
     */
    public $table;
    /**
     * The database table the table column is associated to.
     *
     * @property string $databaseDefaultTable
     */
    public $databaseDefaultTable;
    /**
     * The database table the table column is associated to for the searching action.
     *
     * @property string $databaseSearchedTable
     */
    public $databaseSearchedTable;
    /**
     * The database column the table column is associated to.
     *
     * @property string $databaseDefaultColumn
     */
    public $databaseDefaultColumn;
    /**
     * The database column the table column is associated to for the searching action.
     *
     * @property string $databaseSearchedColumns
     */
    public $databaseSearchedColumns;
    /**
     * The table column sortable status.
     *
     * @property bool $isSortable
     */
    public $isSortable;
    /**
     * The table column custom title.
     *
     * @property string $title
     */
    public $title;
    /**
     * The table column datetime format.
     *
     * @property string $dateTimeFormat
     */
    public $dateTimeFormat;
    /**
     * The table column button classes.
     *
     * @property array $buttonClasses
     */
    public $buttonClasses;
    /**
     * The table column string length limitation.
     *
     * @property int $stringLimit
     */
    public $stringLimit;
    /**
     * The table column url.
     *
     * @property string $url
     */
    public $url;
    /**
     * The table column custom value closure.
     *
     * @property Closure $valueClosure
     */
    public $valueClosure;
    /**
     * The table column custom html output closure.
     *
     * @property Closure $htmlClosure
     */
    public $htmlClosure;
    /**
     * The table column value prepended icon.
     *
     * @property string $icon
     */
    public $icon;
    /**
     * The icon display status when no value is found.
     *
     * @property bool $displayIconWhenNoValue
     */
    public $displayIconWhenNoValue;
    /**
     * The table column classes.
     *
     * @property array $classes
     */
    public $classes;

    /**
     * \Okipa\LaravelTable\Column constructor.
     *
     * @param Table $table
     * @param string|null $databaseColumn
     */
    public function __construct(Table $table, string $databaseColumn = null)
    {
        $this->table = $table;
        $this->databaseDefaultTable = $table->model->getTable();
        $this->databaseDefaultColumn = $databaseColumn;
        $this->title = $databaseColumn ? __('validation.attributes.' . $databaseColumn) : null;
    }

    /**
     * Set the column title or override the default __('validation.attributes.[column key]) title generated from the
     * column name.
     *
     * @param string|null $title
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function title(string $title = null): Column
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Make the column sortable.
     * You also can choose to set the column sorted by default.
     * If no column is sorted by default, the first one will be automatically sorted.
     *
     * @param bool $sortByDefault
     * @param string $sortDirection
     *
     * @return \Okipa\LaravelTable\Column
     * @throws \ErrorException
     */
    public function sortable(bool $sortByDefault = false, string $sortDirection = 'asc'): Column
    {
        $this->table->sortableColumns->push($this);
        $this->isSortable = true;
        if ($sortByDefault) {
            $this->sortByDefault($sortDirection);
        }

        return $this;
    }

    /**
     * Sort the column by default.
     *
     * @param string $sortDirection
     *
     * @throws \ErrorException
     */
    protected function sortByDefault($sortDirection = 'asc')
    {
        if ($this->table->sortBy || $this->table->sortDir) {
            $errorMessage = 'The table is already sorted by the « ' . $this->table->sortBy
                . ' » database column. You only can sort a table column by default once.';
            throw new ErrorException($errorMessage);
        }
        $this->table->sortBy = $this->databaseDefaultColumn;
        $acceptedDirections = ['asc', 'desc'];
        $errorMessage = 'Invalid « $sortDirection » second argument for « sortable() » method. Has to be « asc » or '
            . '« desc ». « ' . $sortDirection . ' » given.';
        if (! in_array($sortDirection, $acceptedDirections)) {
            throw new InvalidArgumentException($errorMessage);
        }
        $this->table->sortDir = $sortDirection;
    }

    /**
     * Make the table column searchable.
     * The first param allows you to precise the searched database table (can references a database table alias).
     * The second param allows you to precise the searched database attributes (if not precised, the table database
     * column is searched).
     *
     * @param string $databaseSearchedTable
     * @param array $databaseSearchedColumns
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function searchable(string $databaseSearchedTable = null, array $databaseSearchedColumns = []): Column
    {
        $this->table->searchableColumns->push($this);
        $this->databaseSearchedTable = $databaseSearchedTable;
        $this->databaseSearchedColumns = $databaseSearchedColumns;

        return $this;
    }

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

        return $this;
    }

    /**
     * Display the column as a button with the given classes.
     *
     * @param array $buttonClasses
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function button(array $buttonClasses = []): Column
    {
        $this->buttonClasses = $buttonClasses;

        return $this;
    }

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

        return $this;
    }

    /**
     * Add an icon before the displayed value.
     * Set the second param as true if you want the icon to be displayed even if the column has no value.
     *
     * @param string $icon
     * @param bool $displayIconWhenNoValue
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function icon(string $icon, bool $displayIconWhenNoValue = false): Column
    {
        $this->icon = $icon;
        $this->displayIconWhenNoValue = $displayIconWhenNoValue;

        return $this;
    }

    /**
     * Wrap the column value into a <a></a> component.
     * You can declare the link as a string or as a closure which will let you manipulate the following attributes :
     * $model, $column.
     * If no url is declared, it will be set with the column value.
     *
     * @param string|Closure|null $url
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function link($url = null): Column
    {
        $this->url = $url ?: true;

        return $this;
    }

    /**
     * Display a custom value for the column.
     * The closure let you manipulate the following attributes : $model, $column.
     *
     * @param Closure $valueClosure
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function value(Closure $valueClosure): Column
    {
        $this->valueClosure = $valueClosure;

        return $this;
    }

    /**
     * Display a custom HTML for the column.
     * The closure let you manipulate the following attributes : $model, $column.
     *
     * @param Closure $htmlClosure
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function html(Closure $htmlClosure): Column
    {
        $this->htmlClosure = $htmlClosure;

        return $this;
    }

    /**
     * Set the custom classes that will be applied only on this column.
     *
     * @param array $classes
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function classes(array $classes): Column
    {
        $this->classes = $classes;

        return $this;
    }
}
