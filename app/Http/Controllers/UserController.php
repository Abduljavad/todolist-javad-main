<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
    public function store(CreateNewUserRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json($user, 201);
    }

    // update details of a user
    public function update(UpdateUserRequest $request, User $userId)
    {
        // $user = User::findOrFail($request->userId);
        $userId->update($request->validated());
        return response()->json([
            'message'=>'Updated'
        ],200);
    }

    // delete a user
    public function destroy(User $userId)
    {
        $userId->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 204);
    }
}
