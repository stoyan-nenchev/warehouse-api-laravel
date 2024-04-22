<?php

namespace App\Filters;

abstract class QueryOperatorConstants {
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const ILIKE = 'ilike';
    public const LIKE = 'like';
    public const LESS_THAN = '<';
    public const LESS_THAN_OR_EQUAL = '<=';
    public const GREATER_THAN = '>';
    public const GREATER_THAN_OR_EQUAL = '>=';
}