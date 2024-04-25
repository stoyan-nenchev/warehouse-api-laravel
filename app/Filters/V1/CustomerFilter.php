<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use App\Filters\QueryJoinConstants;
use App\Filters\QueryOperatorConstants;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerFilter extends ApiFilter {
    protected $allowedParams = [
        'name' => [QueryOperatorConstants::ILIKE],
        'email' => [QueryOperatorConstants::EQUAL],
        'address' => [QueryOperatorConstants::ILIKE]
    ];

    public function search(Request $request) {
        $includeInvoices = $request->input('includeInvoices', false);
        $customerQuery = Customer::query();

        if ($includeInvoices) {
            parent::addJoin('invoices', 'customers.id', 'invoices.customer_id', QueryJoinConstants::JOIN);
        }

        return parent::createSearchQuery($request, $customerQuery);
    }
}