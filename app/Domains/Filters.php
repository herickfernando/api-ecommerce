<?php

namespace App\Domains;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

abstract class Filters
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $searchTerm;

    public function __construct(string $searchTerm = '')
    {
        $this->searchTerm = $searchTerm;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        if (!empty($this->searchTerm)) {
            $this->search($this->searchTerm);
        }
    }

    /**
     * @param string $value
     * @return Builder
     */
    private function search(string $value)
    {
        foreach ($this->getAllowedFilters() as $column) {
            $operator = 'like';

            $valueSearch = '%' . strtoupper($value) . '%';
            $columnRaw = DB::raw('UPPER(CAST(' . $column . ' AS varchar))');

            $this
                ->builder
                ->orWhere($columnRaw, $operator, $valueSearch)
                ->orWhere($columnRaw, '=', $valueSearch);
        }

        return $this->builder;
    }

    /**
     * Get list of columns allowed for search
     * @return array
     */
    abstract protected function getAllowedFilters();
}
