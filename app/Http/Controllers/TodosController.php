<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Todo::where('user_id', auth()->user()->id)->get();
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'completed' => 'required|boolean',
        ]);

         $todo =   Todo::create([
             'user_id' => auth()->user()->id,
             'title' => request('title'),
             'completed' => request('completed')
         ]);
         
         // return JSON response with code 201
         return response($todo,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id != auth()->user()->id) {
            // To prevent logged in users from updating other users todos
           return response()->json('Unauthorized',403);
        }
        $data = $request->validate([
            'title' => 'required|string',
            'completed' => 'required|boolean',
        ]);

       

         $todo->update($data);
         
         // return JSON response with code 201
         return response($todo,200);
    }

    // Chs
    public function checkAll(Request $request)
    {
        $data = $request->validate([
           
            'completed' => 'required|boolean',
        ]);

       

        Todo::where('user_id',auth()->user()->id)->update($data);
         
         // return JSON response with code 201
         return response('Checked all todos',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
         if ($todo->user_id != auth()->user()->id) {
         // To prevent logged in users from updating other users todos
         return response()->json('Unauthorized',401);
         }
        $todo->delete();

        // return JSON response with code 201
        return response('Deleted Todo item', 200);
    }

    public function destroyCompleted(Request $request)
    {

        $todosToDelete = $request->todos;  
        
        $userTodoIds = auth()->user()->todos->map(function ($todo){
            return $todo->id;
        });

       $valid = collect($todosToDelete)->every(function($value, $key) use($userTodoIds){
            return $userTodoIds->contains($value);
        });

        if(!$valid){
            return response()->json('Unauthorized',401);
        }
        
        $data = $request->validate([
    /* 
    'todos' will be passed in as an array to be converted to PHP array of
    of ids that are
     */
            'todos' => 'required|array',
        ]);


        
        Todo::destroy($request->todos);

        // return JSON response with code 201
        return response('Cleared completed todos', 200);
    }
}
