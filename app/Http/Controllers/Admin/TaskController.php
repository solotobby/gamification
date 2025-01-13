<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(){
        $task = Task::all();
        return view('admin.task.index', ['tasks' => $task]);
    }

    public function store(Request $request){
        Task::create($request->all());
        return back()->with('success', 'Task Created!');
    }

    public function updateTask(Request $request){

        $task = Task::where('id', $request->_id)->first();
        $task->status = $request->staus;
        $task->save();
        return back()->with('success', 'Task Updated!');
        
    }
}
