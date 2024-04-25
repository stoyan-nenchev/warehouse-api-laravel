<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;


#[
    OA\Info(version: "1.0.0",  title: "Warehouse-API Documentation"),
    OA\SecurityScheme( securityScheme: 'bearerAuth', type: "http", name: "Authorization", in: "header", scheme: "bearer"),
]
abstract class Controller
{
    //
}
