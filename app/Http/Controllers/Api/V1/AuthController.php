<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}
