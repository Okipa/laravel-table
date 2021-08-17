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

    public function appendData(array $appendedToPaginator): Table
    {
        $appendedToPaginator = array_filter($appendedToPaginator);
        $this->appendedToPaginator = $appendedToPaginator;

        return $this;
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

    abstract public function getDestroyConfirmationClosure(): Closure|null;

    protected function paginateFromQuery(Builder $query): void
    {
        $perPage = $this->getRowsNumberValue() ?: $query->count();
        $this->paginator = $query->paginate($perPage);
        $this->getPaginator()->appends(array_merge([
            $this->getRowsNumberField() => $this->getRowsNumberValue(),
            $this->getSearchField() => $this->searchValue,
            $this->getSortByField() => $this->getSortByValue(),
            $this->getSortDirField() => $this->getSortDirValue(),
        ], $this->getAppendedToPaginator()));
    }

    abstract public function getRowsNumberValue(): int|null;

    abstract public function getRowsNumberField(): string;

    abstract public function getSearchField(): string;

    abstract public function getSortByField(): string;

    abstract public function getSortByValue(): string|null;

    abstract public function getSortDirField(): string;

    abstract public function getSortDirValue(): string|null;

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
}
