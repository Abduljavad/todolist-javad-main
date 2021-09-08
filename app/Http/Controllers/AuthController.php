<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $contents = $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);

        if (Auth::attempt($contents)) {
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
