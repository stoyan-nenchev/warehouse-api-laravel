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
            new OA\QueryParameter(parameter: 'page', name: 'page', description: 'The number of page', schema: new OA\Schema(type: 'int64')),
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

    #[OA\Post(
        path: "/api/v1/customers/bulk",
        summary: "Create multiple customers",
        tags: ["Customer Controller"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        required: ["name", "address", "email"],
                        properties: [
                            new OA\Property(property: 'name', description: "Customer full name", type: "string"),
                            new OA\Property(property: 'address', description: "Customer address", type: "string"),
                            new OA\Property(property: 'email', description: "Customer email", type: "string")
                        ]
                    )
                )
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: "Customers created successfully"),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: "Unprocessable entity"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
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

    #[OA\Put(
        path: "/api/v1/customers",
        summary: "Update a customer",
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
            new OA\Response(response: Response::HTTP_OK, description: "Customer updated successfully"),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: "Unprocessable entity"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return new CustomerResource($customer);
    }

    #[OA\Delete(
        path: "/api/v1/customers/{id}",
        summary: "Delete a customer by id",
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
            new OA\Response(response: Response::HTTP_OK, description: "Customer deleted successfully"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: "Not found."),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return response()->json(['message' => 'Customer deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete customer', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
