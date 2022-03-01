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

    protected string $generatedHiddenFields = '';

    public function appendData(array $appendedToPaginator): Table
    {
        $appendedToPaginator = array_filter($appendedToPaginator);
        $this->appendedToPaginator = $appendedToPaginator;
        $this->generatedHiddenFields = $this->generateHiddenInputsFromArray($appendedToPaginator);

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    protected function generateHiddenInputsFromArray(array $data = [], $namePrefix = ''): string
    {
        if (! $data) {
            return '';
        }
        $html = '';
        $namePrefix = trim($namePrefix);
        foreach ($data as $key => $value) {
            $keyEsc = htmlentities($key);
            if ($namePrefix !== '') {
                $keyEsc = $namePrefix . "[{$keyEsc}]";
            }
            if (is_array($value)) {
                $html .= $this->generateHiddenInputsFromArray($value, $keyEsc);
            } else {
                $valueEsc = htmlentities($value);
                $html .= "<input type=\"hidden\" name=\"{$keyEsc}\" value=\"{$valueEsc}\">" . PHP_EOL;
            }
        }

        return $html;
    }

    public function getGeneratedHiddenFields(): string
    {
        return $this->generatedHiddenFields;
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

    abstract public function getDestroyConfirmationClosure(): ?Closure;

    abstract public function getRowsNumberField(): string;

    abstract public function getSearchField(): string;

    abstract public function getSortByField(): string;

    abstract public function getSortByValue(): ?string;

    abstract public function getSortDirField(): string;

    abstract public function getSortDirValue(): ?string;

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
