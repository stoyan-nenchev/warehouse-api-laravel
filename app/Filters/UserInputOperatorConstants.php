<?php

namespace App\Filters;

abstract class UserInputOperatorConstants {
    public const EQUAL = 'eq';
    public const NOT_EQUAL = 'neq';
    public const ILIKE = 'ili';
    public const LIKE = 'li';
    public const LESS_THAN = 'lt';
    public const LESS_THAN_OR_EQUAL = 'lte';
    public const GREATER_THAN = 'gt';
    public const GREATER_THAN_OR_EQUAL = 'gte';
}