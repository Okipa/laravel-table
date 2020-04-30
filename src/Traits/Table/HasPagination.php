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

    protected ?array $appendedValues = [];

    protected ?array $appendedHiddenFields = [];

    abstract public function getRowsNumberValue(): ?int;

    abstract public function getRowsNumberField(): string;

    abstract public function getSearchField(): string;

    abstract public function getSortByField(): string;

    abstract public function getSortByValue(): ?string;

    abstract public function getSortDirField(): string;

    abstract public function getSortDirValue(): ?string;

    abstract protected function addClassesToRow(Model $model): void;

    abstract protected function disableRow(Model $model): void;

    abstract public function getDestroyConfirmationClosure(): ?Closure;

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

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    protected function extractHiddenFieldsToGenerate(array $appendedValues): array
    {
        $httpArguments = explode('&', http_build_query($appendedValues));
        $appendedHiddenFields = [];
        foreach ($httpArguments as $httpArgument) {
            $argument = explode('=', $httpArgument);
            $appendedHiddenFields[urldecode(head($argument))] = last($argument);
        }

        return $appendedHiddenFields;
    }

    public function getAppendedHiddenFields(): ?array
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

    protected function paginateFromQuery(Builder $query): void
    {
        $this->paginator = $query->paginate($this->getRowsNumberValue() ?: $query->count());
        $this->getPaginator()->appends(array_merge([
            $this->getRowsNumberField() => $this->getRowsNumberValue(),
            $this->getSearchField() => $this->searchValue,
            $this->getSortByField() => $this->getSortByValue(),
            $this->getSortDirField() => $this->getSortDirValue(),
        ], $this->getAppendedValues()));
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    public function getAppendedValues(): array
    {
        return $this->appendedValues;
    }

    protected function transformPaginatedRows(): void
    {
        $this->getPaginator()->getCollection()->transform(function (Model $model) {
            $this->addClassesToRow($model);
            $this->disableRow($model);
            if ($this->getDestroyConfirmationClosure()) {
                $model->destroyConfirmationAttributes = ($this->getDestroyConfirmationClosure())($model);
            }

            return $model;
        });
    }
}
