<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // list all users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // details of a user
    public function show(Request $request)
    {
        $userId = $request->user;

        // finding user from db
        try {
            $user = User::findOrFail($userId);
            return response()->json($user);
        } catch (Exception $e) {
            // return error response
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }
    }

    // create a new user
    public function store(Request $request)
    {
        $contents = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        // hashing only password field
        $contents['password'] = Hash::make($contents['password']);

        $user = User::create($contents);
        return response()->json($user, 201);
    }

    // update details of a user
    public function update(Request $request)
    {
        $contents = $request->validate([
            'name' => 'max:255',
        ]);

        try {
            $user = User::findOrFail($request->userId);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }

        $user = $user->update($contents);
        return response()->json($user);
    }

    // delete a user
    public function destroy(Request $request)
    {
        try {
            $user = User::findOrFail($request->userId);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 204);
    }
}
