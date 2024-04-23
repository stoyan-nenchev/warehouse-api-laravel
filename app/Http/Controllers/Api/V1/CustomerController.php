<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\CustomerFilter;
use App\Http\Requests\V1\BulkStoreCustomerRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /** 
    *  @OA\Get(
    *      path="/api/v1/customers",
    *      operationId="index",
    *      tags={"Customer Controller"},
    *      summary="Get customers",
    *      description="Returns paginated customers",
    *      security={{"bearerAuth": {}}},
    *      @OA\Parameter(
    *          name="Accept",
    *          in="header",
    *          required=true,
    *          @OA\Schema(
    *              type="string",
    *              default="application/json"
    *          ),
    *          description="Content type"
    *      ),
    *      @OA\Parameter(
    *          name="name[ili]",
    *          in="header",
    *          description="Filter customers by name",
    *          @OA\Schema(type="string")
    *      ),
    *      @OA\Parameter(
    *          name="includeInvoices",
    *          in="header",
    *          description="Include invoices in the response",
    *          @OA\Schema(type="boolean")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation."
    *       ),
    *      @OA\Response(response=401, description="Unauthorized."),
    *   ) 
    *
    * Returns list of projects
    */
    public function index(Request $request)
    {
        $filter = new CustomerFilter();
        $customerQuery = $filter->search($request);
        
        return new CustomerCollection($customerQuery->paginate());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Store newly created resources in storage - bulk.
     */
    public function bulkStore(BulkStoreCustomerRequest $request)
    {
        Customer::insert($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
