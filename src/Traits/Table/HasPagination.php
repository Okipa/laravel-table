<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Table;

trait HasPagination
{
    protected LengthAwarePaginator $paginator;

    protected array $appendedToPaginator = [];

    protected array $appendedHiddenFields = [];

    /**
     * Add an array of arguments to append to the paginator and to the following table actions: row number selection,
     * searching, search canceling, sorting.
     *
     * @param array $appendedToPaginator
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function appendData(array $appendedToPaginator): Table
    {
        $this->appendedToPaginator = $appendedToPaginator;
        $this->appendedHiddenFields = $this->extractHiddenFieldsToGenerate($appendedToPaginator);

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    protected function extractHiddenFieldsToGenerate(array $appendedToPaginator): array
    {
        $httpArguments = explode('&', http_build_query($appendedToPaginator));
        $appendedHiddenFields = [];
        foreach ($httpArguments as $httpArgument) {
            $argument = explode('=', $httpArgument);
            $appendedHiddenFields[urldecode(head($argument))] = last($argument);
        }

        return $appendedHiddenFields;
    }

    public function getAppendedHiddenFields(): array
    {
        return $this->appendedHiddenFields;
    }

    public function getNavigationStatus(): string
    {
        return (string) __('Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>', [
            'start' => ($this->getPaginator()->perPage() * ($this->getPaginator()->currentPage() - 1)) + 1,
            'stop' => $this->getPaginator()->getCollection()->count()
                + (($this->getPaginator()->currentPage() - 1) * $this->getPaginator()->perPage()),
            'total' => (int) $this->getPaginator()->total(),
        ]);
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    protected function paginateFromQuery(Builder $query): void
    {
        /** @var int|null $perPage */
        $perPage = $this->getRowsNumberValue() ?: $query->count();
        $this->paginator = $query->paginate($perPage);
        $this->getPaginator()->appends(array_merge([
            $this->getRowsNumberField() => $this->getRowsNumberValue(),
            $this->getSearchField() => $this->searchValue,
            $this->getSortByField() => $this->getSortByValue(),
            $this->getSortDirField() => $this->getSortDirValue(),
        ], $this->getAppendedToPaginator()));
    }

    abstract public function getRowsNumberValue(): ?int;

    abstract public function getRowsNumberField(): string;

    abstract public function getSearchField(): string;

    abstract public function getSortByField(): string;

    abstract public function getSortByValue(): ?string;

    abstract public function getSortDirField(): string;

    abstract public function getSortDirValue(): ?string;

    public function getAppendedToPaginator(): array
    {
        return $this->appendedToPaginator;
    }

    protected function transformPaginatedRows(): void
    {
        $this->getPaginator()->getCollection()->transform(function (Model $model) {
            $this->addClassesToRow($model);
            $this->disableRow($model);
            $this->defineRowConfirmationHtmlAttributes($model);

            return $model;
        });
    }

    abstract protected function addClassesToRow(Model $model): void;

    abstract protected function disableRow(Model $model): void;

    abstract public function getDestroyConfirmationClosure(): ?Closure;
}
