<?php

namespace Okipa\LaravelTable;

use Closure;
use ErrorException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Traits\TableClassesCustomizations;
use Okipa\LaravelTable\Traits\TableColumnsValidationChecks;
use Okipa\LaravelTable\Traits\TableInteractions;
use Okipa\LaravelTable\Traits\TableRoutesValidationChecks;
use Okipa\LaravelTable\Traits\TableTemplatesCustomizations;

/** @SuppressWarnings(PHPMD.ExcessivePublicCount) */
class Table implements Htmlable
{
    use TableTemplatesCustomizations;
    use TableClassesCustomizations;
    use TableRoutesValidationChecks;
    use TableColumnsValidationChecks;
    use TableInteractions;

    protected string $identifier;

    protected Model $model;

    protected bool $rowsNumberSelectionActivation;

    protected Collection $sortableColumns;

    protected Collection $searchableColumns;

    protected Request $request;

    protected array $routes = [];

    protected Collection $columns;

    protected ?Closure $queryClosure;

    protected Collection $disabledRows;

    protected LengthAwarePaginator $paginatedList;

    protected Closure $destroyConfirmationClosure;

    protected array $appendedValues = [];

    protected array $appendedHiddenFields = [];

    protected Collection $results;

    public function __construct()
    {
        $this->initializeDefaultComponents();
        $this->initializeTableDefaultClasses();
        $this->rowsValue = config('laravel-table.value.rowsNumber');
        $this->rowsNumberSelectionActivation = (bool) config('laravel-table.value.rowsNumberSelectionActivation');
        $this->sortableColumns = new Collection();
        $this->searchableColumns = new Collection();
        $this->request = request();
        $this->columns = new Collection();
        $this->disabledRows = new Collection();
        $this->results = new Collection();
    }

    public function setSortByValue(string $sortByValue): void
    {
        $this->sortByValue = $sortByValue;
    }

    public function setSortDirValue(string $sortDirValue): void
    {
        $this->sortDirValue = $sortDirValue;
    }

    public function getSortByValue(): string
    {
        return $this->sortByValue;
    }

    public function getSortDirValue(): string
    {
        return $this->sortDirValue;
    }

    public function getSortableColumns(): Collection
    {
        return $this->sortableColumns;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getRowsNumberSelectionActivation(): bool
    {
        return $this->rowsNumberSelectionActivation;
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
        $this->rowsValue = $rows;

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
     * @param \Closure $queryClosure
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
        $this->disabledRows->push([
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
        $this->getColumns()->push($column);

        return $column;
    }

    public function getColumns(): Collection
    {
        return $this->columns;
    }

    /**
     * Add a result row that will be displayed at the bottom of the table.
     *
     * @return \Okipa\LaravelTable\Result
     */
    public function result(): Result
    {
        $result = new Result;
        $this->getResults()->push($result);

        return $result;
    }

    public function getResults(): Collection
    {
        return $this->results;
    }

    /**
     * Get the searchable columns titles.
     *
     * @return string
     */
    public function searchableTitles(): string
    {
        return $this->getSearchableColumns()->implode('title', ', ');
    }

    public function getSearchableColumns(): Collection
    {
        return $this->searchableColumns;
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

        return $this->getColumns()->count() + $extraColumnsCount;
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
        return (string) __('Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>', [
            'start' => ($this->getPaginatedList()->perPage() * ($this->getPaginatedList()->currentPage() - 1)) + 1,
            'stop' => $this->getPaginatedList()->count()
                + (($this->getPaginatedList()->currentPage() - 1) * $this->getPaginatedList()->perPage()),
            'total' => (int) $this->getPaginatedList()->total(),
        ]);
    }

    public function getPaginatedList(): LengthAwarePaginator
    {
        return $this->paginatedList;
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     * @throws \ErrorException
     */
    public function toHtml(): string
    {
        $this->configure();

        return view('laravel-table::' . $this->tableTemplatePath, ['table' => $this])->toHtml();
    }

    /**
     * Execute the whole table configuration.
     *
     * @return void
     * @throws \ErrorException
     */
    public function configure(): void
    {
        $this->checkRoutesValidity($this->routes);
        $this->checkIfAtLeastOneColumnIsDeclared();
        $this->handleRequestInteractionValues();
        $this->generateEntitiesListFromQuery();
    }

    /**
     * Generate the entities list.
     *
     * @return void
     */
    protected function generateEntitiesListFromQuery(): void
    {
        $query = $this->getModel()->query();
        $this->applyQueryClosure($query);
        $this->checkColumnsValidity($query);
        $this->applySearchClauses($query);
        $this->applySortClauses($query);
        $this->paginateList($query);
        $this->applyClosuresOnPaginatedList();
    }

    public function getModel(): Model
    {
        return $this->model;
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
        $closure = $this->getQueryClosure();
        if ($closure) {
            $closure($query);
        }
    }

    public function getQueryClosure(): ?Closure
    {
        return $this->queryClosure;
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
        $this->paginatedList = $query->paginate($this->rowsValue ?: $query->count());
        $this->getPaginatedList()->appends(array_merge([
            $this->rowsField => $this->rowsValue,
            $this->searchField => $this->searchValue,
            $this->sortByField => $this->getSortByValue(),
            $this->sortDirField => $this->getSortDirValue(),
        ], $this->getAppendedValues()));
    }

    public function getAppendedValues(): array
    {
        return $this->appendedValues;
    }

    /**
     * Apply the closures on the paginated list.
     *
     * @return void
     */
    protected function applyClosuresOnPaginatedList(): void
    {
        $this->getPaginatedList()->getCollection()->transform(function ($model) {
            $this->rowsConditionalClasses->each(function ($row) use ($model) {
                $model->conditionnalClasses = ($row['closure'])($model) ? $row['classes'] : null;
            });
            $this->getDisabledRows()->each(function ($row) use ($model) {
                $model->disabledClasses = ($row['closure'])($model) ? $row['classes'] : null;
            });
            if ($this->getDestroyConfirmationClosure()) {
                $model->destroyConfirmationAttributes = ($this->getDestroyConfirmationClosure())($model);
            }

            return $model;
        });
    }

    public function getDisabledRows(): Collection
    {
        return $this->disabledRows;
    }

    public function getDestroyConfirmationClosure(): Closure
    {
        return $this->destroyConfirmationClosure;
    }
}
