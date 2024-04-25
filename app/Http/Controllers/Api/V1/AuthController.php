<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request) 
    {    
        $request->validated($request->only(['name', 'email', 'password']));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['token', $token]);
    }

    public function login(LoginUserRequest $request) 
    {
        $request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only('email', 'password'))) {
            return response('Invalid email or password.', 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['token', $token]);
    }

    public function logout() 
    {
        Auth::user()->currentAccessToken()->delete();

        return response('User was deleted.');
    }

    #[OA\Get(
        path: "/api/v1/setup",
        summary: "Get auth tokens",
        tags: ["Auth Controller"],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Tokens retrieved."),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function setup() {
        $credentials = [
            'email' => 'admin@admin.com',
            'password' => 'password'
        ];
    
        if (!Auth::attempt($credentials)) {
            $user = new User();
    
            $user->name = 'Admin';
            $user->email = $credentials['email'];
            $user->password = bcrypt($credentials['password']);
    
            $user->save();
        }
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            $adminToken = $user->createToken('admin-token', ['create','update','delete']);
            $updateToken = $user->createToken('update-token', ['create','update']);
            $basicToken = $user->createToken('basic-token', ['none']);
    
            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken
            ];
        }
    }
}
