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
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class CustomerController extends Controller
{

    #[OA\Get(
        path: "/api/v1/customers",
        summary: "Get paginated customers",
        tags: ["Customer Controller"],
        security: [
            [
                'bearerAuth' => []
            ]
        ],
        parameters: [
            new OA\QueryParameter(parameter: 'name', name: 'name', description: 'The name of the customer - case insensitive', schema: new OA\Schema(type: 'string')),
            new OA\QueryParameter(parameter: 'email', name: 'email', description: 'The customer email - exact match', schema: new OA\Schema(type: 'string')),
            new OA\QueryParameter(parameter: 'address', name: 'address', description: 'The address of the customer - case insensitive', schema: new OA\Schema(type: 'string')),
            new OA\QueryParameter(parameter: 'includeInvoices', name: 'includeInvoices', description: 'Include customer invoices', schema: new OA\Schema(type: 'boolean')),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Customers retrieved."),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    /**
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

    #[OA\Post(
        path: "/api/v1/customers",
        summary: "Create a customer",
        tags: ["Customer Controller"],
        requestBody: new OA\RequestBody(required: true,
                content: new OA\MediaType(mediaType: "application/json",
                schema: new OA\Schema(required: ["name", "address", "email"],
                        properties: [
                            new OA\Property(property: 'name', description: "Customer full name", type: "string"),
                            new OA\Property(property: 'address', description: "Customer address", type: "string"),
                            new OA\Property(property: 'email', description: "Customer email", type: "string")
    ]))),
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: "Customer created successfully"),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: "Unprocessable entity"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
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

    #[OA\Get(
        path: "/api/v1/customers/{id}",
        summary: "Get a customer by id",
        tags: ["Customer Controller"],
        security: [
            [
                'bearerAuth' => []
            ]
        ],
        parameters: [
            new OA\PathParameter(name: 'id'),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Customer found successfully"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: "Not found."),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
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
