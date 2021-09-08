<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Exception;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $todos = $user->todos()->get();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $contents = $request->validate([
            'todo' => 'required|max:255',
        ]);
        $contents['completed'] = false;

        $user = $request->user();
        $todo = $user->todos()->create($contents);

        return response()->json($todo, 201);
    }

    public function update(Request $request)
    {
        try {
            $todo = Todo::findOrFail($request->todoId);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Todo Not Found'
            ], 404);
        }

        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $contents = $request->validate([
            'completed' => 'required|boolean',
        ]);
        $todo->update($contents);

        return response()->json($todo);
    }

    public function destroy(Request $request)
    {
        try {
            $todo = Todo::findOrFail($request->todoId);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Todo Not Found'
            ], 404);
        }

        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $todo->delete();

        return response()->json([
            'message' => 'Todo Deleted'
        ], 204);
    }
}
