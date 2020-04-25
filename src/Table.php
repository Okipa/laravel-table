<?php

namespace Okipa\LaravelTable;

use Closure;
use ErrorException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Traits\TableClassesCustomizations;
use Okipa\LaravelTable\Traits\TableColumnsValidationChecks;
use Okipa\LaravelTable\Traits\TableInteractions;
use Okipa\LaravelTable\Traits\TableRoutesValidationChecks;
use Okipa\LaravelTable\Traits\TableTemplatesCustomizations;

class Table implements Htmlable
{
    use TableTemplatesCustomizations;
    use TableClassesCustomizations;
    use TableRoutesValidationChecks;
    use TableColumnsValidationChecks;
    use TableInteractions;

    /** @property string $identifier */
    public $identifier;

    /** @property \Illuminate\Database\Eloquent\Model $model */
    public $model;

    /** @property bool $rowsNumberSelectionActivation */
    public $rowsNumberSelectionActivation;

    /** @property \Illuminate\Support\Collection $sortableColumns */
    public $sortableColumns;

    /** @property \Illuminate\Support\Collection $searchableColumns */
    public $searchableColumns;

    /** @property \Illuminate\Http\Request $request */
    public $request;

    /** @property array $routes */
    public $routes = [];

    /** @property \Illuminate\Support\Collection $columns */
    public $columns;

    /** @property Closure $queryClosure */
    public $queryClosure;

    /** @property \Illuminate\Support\Collection $disableRows */
    public $disableRows;

    /** @property \Illuminate\Pagination\LengthAwarePaginator $list */
    public $list;

    /** @property Closure $destroyConfirmationClosure */
    public $destroyConfirmationClosure;

    /** @property array $appendedValues */
    public $appendedValues = [];

    /** @property array $appendedHiddenFields */
    public $appendedHiddenFields = [];

    /** @property \Illuminate\Support\Collection $results */
    public $results;

    /**
     * Table constructor.
     */
    public function __construct()
    {
        $this->initializeDefaultComponents();
        $this->initializeTableDefaultClasses();
        $this->rows = config('laravel-table.value.rowsNumber');
        $this->rowsNumberSelectionActivation = config('laravel-table.value.rowsNumberSelectionActivation');
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function model(string $tableModel): Table
    {
        $this->model = app()->make($tableModel);

        return $this;
    }

    /**
     * Set the table identifier, in order to automatically generate its id and to customize all the interaction fields
     * in case of multiple tables used on a single view : the interactions with the table like sorting, searching an
     * more will only have an impact on the identified table.
     *
     * @param string $identifier
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function identifier(string $identifier): Table
    {
        $this->identifier = Str::slug($identifier);
        $this->redefineInteractionFieldsFromIdentifier();

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
     * Override the config default number of rows displayed on the table.
     * The default number of displayed rows is defined in the config('laravel-table.value.rowsNumber') config value.
     *
     * @param int|null $rows
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function rowsNumber(?int $rows): Table
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Override the default rows number selection activation status.
     * Calling this method displays a rows number input that enable the user to choose how much rows to show.
     * The default rows number selection activation status is defined in the
     * config('laravel-table.value.rowsNumberSelectionActivation') config value.
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
     * The closure let you manipulate the following attribute : \Illuminate\Database\Eloquent\Builder $query.
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
     * The closure let you manipulate the following attribute : \Illuminate\Database\Eloquent\Model $model.
     *
     * @param \Closure $rowDisableClosure
     * @param array $classes
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function disableRows(Closure $rowDisableClosure, array $classes = []): Table
    {
        $this->disableRows->push([
            'closure' => $rowDisableClosure,
            'classes' => ! empty($classes) ? $classes : config('laravel-table.classes.disabled'),
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
        $extraColumnsCount = $this->isRouteDefined('show')
        || $this->isRouteDefined('edit')
        || $this->isRouteDefined('destroy') ? 1 : 0;

        return $this->columns->count() + $extraColumnsCount;
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

    /**
     * Get the route from its key.
     *
     * @param string $routeKey
     * @param array $params
     *
     * @return string
     */
    public function route(string $routeKey, array $params = []): string
    {
        $this->checkRouteIsDefined($routeKey);

        return route(
            $this->routes[$routeKey]['name'],
            array_merge($params, Arr::get($this->routes[$routeKey], 'params', []))
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
     * The closure let you manipulate the following attribute : \Illuminate\Database\Eloquent\Model $model.
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
        return (string) __('laravel-table::laravel-table.navigation', [
            'start' => ($this->list->perPage() * ($this->list->currentPage() - 1)) + 1,
            'stop' => $this->list->count() + (($this->list->currentPage() - 1) * $this->list->perPage()),
            'total' => (int) $this->list->total(),
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
        $this->handleRequestInteractionValues();
        $this->generateEntitiesListFromQuery();

        return view('laravel-table::' . $this->tableComponentPath, ['table' => $this]);
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
        $closure = $this->queryClosure;
        if ($closure) {
            $closure($query);
        }
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
        $this->list = $query->paginate($this->rows ?: (int) $query->count());
        $this->list->appends(array_merge([
            $this->rowsField => $this->rows,
            $this->searchField => $this->search,
            $this->sortByField => $this->sortBy,
            $this->sortDirField => $this->sortDir,
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
}
