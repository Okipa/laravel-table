<?php

namespace Okipa\LaravelTable;

use Closure;
use ErrorException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Okipa\LaravelTable\Traits\ClassesCustomizations;
use Okipa\LaravelTable\Traits\ColumnsValidationChecks;
use Okipa\LaravelTable\Traits\RoutesValidationChecks;
use Okipa\LaravelTable\Traits\TemplatesCustomizations;

class Table implements Htmlable
{
    use TemplatesCustomizations;
    use ClassesCustomizations;
    use RoutesValidationChecks;
    use ColumnsValidationChecks;
    public $model;
    public $rows;
    public $rowsNumberSelectionActivation;
    public $sortableColumns;
    public $sortBy;
    public $sortDir;
    public $search;
    public $searchableColumns;
    public $request;
    public $routes = [];
    public $columns;
    public $queryClosure;
    public $disableRows;
    public $list;
    public $destroyConfirmationClosure;
    public $appendedValues = [];
    public $appendedHiddenFields = [];
    public $results;

    /**
     * Table constructor.
     */
    public function __construct()
    {
        $this->initializeDefaultComponents();
        $this->initializeTableDefaultClasses();
        $this->rows = config('laravel-table.rows.number.default');
        $this->rowsNumberSelectionActivation = config('laravel-table.rows.number.selection');
        $this->sortableColumns = new Collection();
        $this->searchableColumns = new Collection();
        $this->request = request();
        $this->columns = new Collection();
        $this->disableRows = new Collection();
        $this->results = new Collection();
    }

    /**
     * Set the model used during the table generation.
     *
     * @param string $tableModel
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function model(string $tableModel): Table
    {
        $this->model = app()->make($tableModel);

        return $this;
    }

    /**
     * Set the request used for the table generation.
     *
     * @param Request $request
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function request(Request $request): Table
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set the routes used for the table generation.
     *
     * @param array $routes
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \ErrorException
     */
    public function routes(array $routes): Table
    {
        $this->checkRoutesValidity($routes);
        $this->routes = $routes;

        return $this;
    }

    /**
     * Override the number of rows to display on the table.
     * The default number of displayed rows is defined in the config('laravel-table.rows.number.default') config value.
     *
     * @param int $rows
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function rowsNumber(int $rows): Table
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Override the default rows number selection activation status.
     * Calling this method displays a rows number input that enable the user to choose how much rows to show.
     * The default rows number selection activation status is defined in the
     * config('laravel-table.rows.number.selection') config value.
     *
     * @param bool $activate
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function rowsNumberSelectionActivation(bool $activate = true): Table
    {
        $this->rowsNumberSelectionActivation = $activate;

        return $this;
    }

    /**
     * Set the query closure that will be executed during the table generation.
     * The closure let you manipulate the following attribute : $query.
     *
     * @param Closure $queryClosure
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function query(Closure $queryClosure): Table
    {
        $this->queryClosure = $queryClosure;

        return $this;
    }

    /**
     * Set the disable lines closure that will be executed during the table generation.
     * The optional second param let you override the classes that will be applied for the disabled lines.
     * The closure let you manipulate the following attribute : $model.
     *
     * @param \Closure $rowDisableClosure
     * @param array    $classes
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function disableRows(Closure $rowDisableClosure, array $classes = []): Table
    {
        $this->disableRows->push([
            'closure' => $rowDisableClosure,
            'classes' => ! empty($classes) ? $classes : config('laravel-table.rows.disabled.classes'),
        ]);

        return $this;
    }

    /**
     * Add a column that will be displayed in the table.
     *
     * @param string|null $databaseColumn
     *
     * @return \Okipa\LaravelTable\Column
     * @throws ErrorException
     */
    public function column(string $databaseColumn = null): Column
    {
        $this->checkModelIsDefined();
        $column = new Column($this, $databaseColumn);
        $this->columns->push($column);

        return $column;
    }

    /**
     * Add a result row that will be displayed at the bottom of the table.
     *
     * @return \Okipa\LaravelTable\Result
     */
    public function result(): Result
    {
        $result = new Result;
        $this->results->push($result);

        return $result;
    }

    /**
     * Get the searchable columns titles.
     *
     * @return string
     */
    public function searchableTitles(): string
    {
        return $this->searchableColumns->implode('title', ', ');
    }

    /**
     * Get the columns count.
     *
     * @return int
     */
    public function columnsCount(): int
    {
        return $this->columns->count();
    }

    /**
     * Get the route from its key.
     *
     * @param string $routeKey
     * @param array  $params
     *
     * @return string
     */
    public function route(string $routeKey, array $params = []): string
    {
        $this->checkRouteIsDefined($routeKey);

        return route(
            $this->routes[$routeKey]['name'],
            array_merge(array_get($this->routes[$routeKey], 'params', []), $params)
        );
    }

    /**
     * Add an array of arguments to append to the paginator and to the following table actions : row number selection,
     * searching, search canceling, sorting.
     *
     * @param array $appendedValues
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function appends(array $appendedValues): Table
    {
        $this->appendedValues = $appendedValues;
        $this->appendedHiddenFields = $this->extractHiddenFieldsToGenerate($appendedValues);

        return $this;
    }

    /**
     * Extract hidden fields to generate from appended values.
     *
     * @param array $appendedValues
     *
     * @return array
     */
    public function extractHiddenFieldsToGenerate(array $appendedValues): array
    {
        $httpArguments = explode('&', http_build_query($appendedValues));
        $appendedHiddenFields = [];
        foreach ($httpArguments as $httpArgument) {
            $argument = explode('=', $httpArgument);
            $appendedHiddenFields[urldecode(head($argument))] = last($argument);
        }

        return $appendedHiddenFields;
    }

    /**
     * Define html attributes on the destroy buttons to handle dynamic javascript destroy confirmations.
     * Beware : the management of the destroy confirmation is on you, if you do not setup a javascript treatment to
     * ask a confirmation, the destroy action will be directly executed.
     *
     * @param \Closure $destroyConfirmationClosure
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function destroyConfirmationHtmlAttributes(Closure $destroyConfirmationClosure): Table
    {
        $this->destroyConfirmationClosure = $destroyConfirmationClosure;

        return $this;
    }

    /**
     * Get the navigation status from the table.
     *
     * @return string
     */
    public function navigationStatus(): string
    {
        return __('laravel-table::laravel-table.navigation', [
            'start' => ($this->list->perPage() * ($this->list->currentPage() - 1)) + 1,
            'stop'  => $this->list->count() + (($this->list->currentPage() - 1) * $this->list->perPage()),
            'total' => $this->list->total(),
        ]);
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     * @throws \ErrorException
     */
    public function toHtml(): string
    {
        return (string) $this->render();
    }

    /**
     * Generate the table html.
     *
     * @return string
     * @throws ErrorException
     */
    public function render(): string
    {
        $this->checkRoutesValidity($this->routes);
        $this->checkIfAtLeastOneColumnIsDeclared();
        $this->handleRequest();
        $this->generateEntitiesListFromQuery();

        return view('laravel-table::' . $this->tableComponentPath, ['table' => $this]);
    }

    /**
     * Handle the request treatments.
     *
     * @return void
     */
    protected function handleRequest(): void
    {
        $validator = Validator::make($this->request->only('rows', 'search', 'sortBy', 'sortDir'), [
            'rows'    => 'required|numeric',
            'search'  => 'nullable|string',
            'sortBy'  => 'nullable|string|in:' . $this->columns->implode('databaseDefaultColumn', ','),
            'sortDir' => 'nullable|string|in:asc,desc',
        ]);
        if ($validator->fails()) {
            $this->request->merge([
                'rows'    => $this->rows ? $this->rows : config('laravel-table.value.rows'),
                'search'  => null,
                'sortBy'  => $this->sortBy,
                'sortDir' => $this->sortDir,
            ]);
        } else {
            $this->rows = $this->request->rows;
            $this->search = $this->request->search;
        }
    }

    /**
     * Generate the entities list.
     *
     * @return void
     */
    protected function generateEntitiesListFromQuery(): void
    {
        $query = $this->model->query();
        $this->applyQueryClosure($query);
        $this->checkColumnsValidity($query);
        $this->applySearchClauses($query);
        $this->applySortClauses($query);
        $this->paginateList($query);
        $this->applyClosuresOnPaginatedList();
        $this->applyResultsClauses();
    }

    /**
     * Apply query closure
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    protected function applyQueryClosure(Builder $query): void
    {
        if ($closure = $this->queryClosure) {
            $closure($query);
        }
    }

    /**
     * Apply search clauses
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    protected function applySearchClauses(Builder $query): void
    {
        if ($searched = $this->request->search) {
            $query->where(function ($subQuery) use ($searched) {
                $this->searchableColumns->map(function (Column $column, int $columnKey) use ($subQuery, $searched) {
                    $databaseSearchedTable = $column->databaseSearchedTable
                        ? $column->databaseSearchedTable
                        : $column->databaseDefaultTable;
                    $operator = $columnKey > 0 ? 'orWhere' : 'where';
                    $databaseSearchedColumns = $column->databaseSearchedColumns
                        ? $column->databaseSearchedColumns
                        : [$column->databaseDefaultColumn];
                    foreach ($databaseSearchedColumns as $searchedDatabaseColumnKey => $searchedDatabaseColumn) {
                        $operator = $searchedDatabaseColumnKey > 0 ? 'orWhere' : $operator;
                        $subQuery->{$operator}(
                            $databaseSearchedTable . '.' . $searchedDatabaseColumn,
                            'like',
                            '%' . $searched . '%'
                        );
                    }
                });
            });
        }
    }

    /**
     * Apply sort clauses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    protected function applySortClauses(Builder $query): void
    {
        $this->sortBy = $this->request->sortBy
            ? $this->request->sortBy
            : ($this->sortBy ? $this->sortBy : optional($this->sortableColumns->first())->databaseDefaultColumn);
        $this->sortDir = $this->request->sortDir
            ? $this->request->sortDir
            : ($this->sortDir ? $this->sortDir : 'asc');
        $query->orderBy($this->sortBy, $this->sortDir);
    }

    /**
     * Paginate the list from the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    protected function paginateList(Builder $query): void
    {
        $this->list = $query->paginate($this->rows);
        $this->list->appends(array_merge([
            'rows'    => $this->rows,
            'search'  => $this->search,
            'sortBy'  => $this->sortBy,
            'sortDir' => $this->sortDir,
        ], $this->appendedValues));
    }

    /**
     * Apply the closures on the paginated list.
     *
     * @return void
     */
    protected function applyClosuresOnPaginatedList(): void
    {
        $this->list->getCollection()->transform(function ($model) {
            $this->rowsConditionalClasses->each(function ($row) use ($model) {
                $model->conditionnalClasses = ($row['closure'])($model) ? $row['classes'] : null;
            });
            $this->disableRows->each(function ($row) use ($model) {
                $model->disabledClasses = ($row['closure'])($model) ? $row['classes'] : null;
            });
            if ($this->destroyConfirmationClosure) {
                $model->destroyConfirmationAttributes = ($this->destroyConfirmationClosure)($model);
            }

            return $model;
        });
    }

    protected function applyResultsClauses()
    {
        $displayedList = $this->list->getCollection();
        $resultsRowsCount = $this->columns->max('resultClosures')->count();
        for ($ii = 0; $ii < $resultsRowsCount; $ii++) {
            $resultRow = new Collection();
            foreach ($this->columns as $column) {
                $result = $column->resultClosures->get($ii) ? $column->resultClosures->get($ii)($displayedList) : null;
                $resultRow->push($result);
            }
            $this->results->push($resultRow);
        };
    }

    /**
     * Check if a route is defined from its key.
     *
     * @param string $routeKey
     *
     * @return bool
     */
    public function isRouteDefined(string $routeKey): bool
    {
        return (isset($this->routes[$routeKey]) || ! empty($this->routes[$routeKey]));
    }
}
