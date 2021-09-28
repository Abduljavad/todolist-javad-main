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
    // removed duplicate function
    // public function store(Request $request)
    // {
    //     $contents = $request->validate([
    //         'todo' => 'required|max:255',
    //     ]);
    //     $contents['completed'] = false;

    //     $user = $request->user();
    //     $todo = $user->todos()->create($contents);

    //     return response()->json($todo, 201);
    // }
    public function store(Request $request)
    {

        // validation must be form request validation
        $contents = $request->validate([
            'todo' => 'required|max:255',
            'file' => 'mimes:jpg,bmp,png,pdf'
        ]);
        // this value can be set within the migration itself ->default(false);
        $contents['completed'] = false;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store("public/files");
            $contents['file'] = $path;
        } else {
            $contents['file'] = null; // no need to set it as null  this value can be set within the migration itself ->nullable
        }

        $user = $request->user();
        $todo = $user->todos()->create($contents);

        return response()->json($todo, 201); // keep response msgs in a same manner
    }

    public function update(Request $request, Todo $todo)
    {
        if(!$this->checkAuth){
            // return response()->json(//message);
        }
        //  add  functionality to update todo 
        $contents = $request->validate([
            'completed' => 'required|boolean',
        ]);
        $todo->update($contents);

        return response()->json($todo);
    }

    public function destroy(Todo $todo)
    {
        // check auth
        if(!$this->checkAuth){
            // return response()->json(//message);
        }
        $todo->delete();
        return response()->json([
            'message' => 'Todo Deleted'
        ], 204);
    }

    private function checkAuth($todo){
        if(auth()->user()->id === $todo->user_id){
            return true;
        }
        return false;

    }
}
