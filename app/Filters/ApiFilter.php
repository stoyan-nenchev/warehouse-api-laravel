<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class ApiFilter {
    protected $allowedParams = [];
    protected $columnMap = [];
    protected $joins = [];

    protected function addJoin(string $table, string $fromColumn, string $toColumn, string $joinType) {
        $this->joins[] = compact('table', 'fromColumn', 'toColumn', 'joinType');
    }

    protected function addJoinClause(Builder &$queryBuilder) {
        foreach ($this->joins as $join) {
            //$queryBuilder->join($join['table'], $join['fromColumn'], QueryOperatorConstants::EQUAL, $join['toColumn']);
            $queryBuilder->with($join['table']);
        }
    }

    public function createSearchQuery(Request $request, Builder $queryBuilder) {
        $searchQuery = [];

        foreach ($this->allowedParams as $param => $operators) {
            $query = $request->query($param);

            if (!isset($query)) {
                continue;
            }
        
            $columnName = $this->columnMap[$param] ?? $param;
            $operatorValue = $this->allowedParams[$columnName][0];

            if ($operatorValue === QueryOperatorConstants::ILIKE || $operatorValue === QueryOperatorConstants::LIKE) {
                $query = strtolower('%'.$query.'%');
            }

            $searchQuery[] = [$columnName, $operatorValue, $query];
        }

        $this->addJoinClause($queryBuilder);
        $queryBuilder->where($searchQuery);
        return $queryBuilder;
    }
}