<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class ApiFilter {
    protected $allowedParams = [];
    protected $columnMap = [];
    protected $operatorMap = [
        UserInputOperatorConstants::EQUAL => QueryOperatorConstants::EQUAL,
        UserInputOperatorConstants::NOT_EQUAL => QueryOperatorConstants::NOT_EQUAL,
        UserInputOperatorConstants::LIKE => QueryOperatorConstants::LIKE,
        UserInputOperatorConstants::ILIKE => QueryOperatorConstants::ILIKE,
        UserInputOperatorConstants::LESS_THAN => QueryOperatorConstants::LESS_THAN,
        UserInputOperatorConstants::LESS_THAN_OR_EQUAL => QueryOperatorConstants::LESS_THAN_OR_EQUAL,
        UserInputOperatorConstants::GREATER_THAN => QueryOperatorConstants::GREATER_THAN,
        UserInputOperatorConstants::GREATER_THAN_OR_EQUAL => QueryOperatorConstants::GREATER_THAN_OR_EQUAL
    ];
    protected $joins = [];

    protected function addJoin(string $table, string $fromColumn, string $toColumn, string $joinType) {
        $this->joins[] = compact('table', 'fromColumn', 'toColumn', 'joinType');
    }

    protected function addJoinClauses(array &$searchQuery) {
        foreach ($this->joins as $join) {
            $searchQuery[] = "{$join['joinType']} {$join['table']} ON {$join['fromColumn']} = {$join['toColumn']} ";
        }
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
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $value = $query[$operator];

                    if ($operator === UserInputOperatorConstants::ILIKE || $operator === UserInputOperatorConstants::LIKE) {
                        $value = strtolower('%'.$value.'%');
                    }

                    $searchQuery[] = [$columnName, $this->operatorMap[$operator], $value];
                }
            }
        }

        $this->addJoinClause($queryBuilder);
        $queryBuilder->where($searchQuery);
        return $queryBuilder;
    }
}