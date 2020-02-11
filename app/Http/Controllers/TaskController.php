<?php

namespace App\Http\Controllers;

use App\Category;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Task::all()->toArray());
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task=Task::create([
            'name'=> $request->name,
            'category_id'=>$request->category_id,
            'user_id'=>$request->user_id,
            'order'=>$request->order
        ]);
        return response()->json([
            'status'=>(bool)$task,
            'data'=>$task,
            'message'=>$task?'Created Succesfully':'Error Creating'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $status=$task->update($request->only(['name','category_id','user_id','order']));
        return response()->json(['status'=>$status,
            'message'=>$status?'Updated Successfully':'Error Updating'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $status=$task->delete();
        return response()->json([
            'status'=>$status,
            'message'=>$status?'Deleted Successfully':'Error Deleting'
        ]);
    }
}
