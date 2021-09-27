<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // request validation done via Form Request Validation
        if (Auth::attempt($request->validated())) {
            $user = $request->user();
            $token = $user->createToken('authToken');

            return response()->json([
                'token' => $token->plainTextToken
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid email and password combination'
            ], 401);
        }
    }

    // public function test(Request $request)
    // {
    //     return response()->json($request->user());
    // }
}
