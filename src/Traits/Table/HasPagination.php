<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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

    protected function transformPaginatedRows(): void
    {
        $this->getPaginator()->getCollection()->transform(function (Model|array $row) {
            $row = $row instanceof Model ? $row->toArray() : $row;
            $this->addClassesToRow($row);
            $this->disableRow($row);
            $this->defineRowConfirmationHtmlAttributes($row);

            return $row;
        });
    }

    protected function generatePaginatorFromEloquent(Builder $query): void
    {
        $perPage = $this->getRowsNumberValue() ?: $query->count();
        $this->paginator = $query->paginate($perPage);
    }

    protected function generatePaginatorFromCollection(): void
    {
        $total = $this->getCollection()->count();
        $perPage = $this->getRowsNumberValue() ?: $total;
        $this->paginator = new LengthAwarePaginator($this->getCollection(), $total, $perPage);
    }

    protected function appendDataToPaginator(): void
    {
        $this->getPaginator()->appends(array_merge([
            $this->getRowsNumberField() => $this->getRowsNumberValue(),
            $this->getSearchField() => $this->searchValue,
            $this->getSortByField() => $this->getSortByValue(),
            $this->getSortDirField() => $this->getSortDirValue(),
        ], $this->getAppendedToPaginator()));
    }

    public function getAppendedToPaginator(): array
    {
        return $this->appendedToPaginator;
    }
}
