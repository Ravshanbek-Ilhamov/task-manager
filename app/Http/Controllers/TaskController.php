<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Area;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
     {
         $query = Task::query();
     
         if (auth()->user()->role !== 'admin') {
             $userAreas = auth()->user()->areas->pluck('id');
     
             $query->whereHas('areas', function ($query) use ($userAreas) {
                 $query->whereIn('areas.id', $userAreas);
             });
         }
     
         if ($request->has('start_date') && $request->has('end_date')) {
             $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
         }
     
         $tasks = $query->paginate(10);
     
         return view('task.task', compact('tasks'));
     }
     
     
     
        
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $areas = Area::all();
        $categories = Category::all();
        return view('task.task_create', compact('categories','areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('tasks', 'public'); 
        }
        
        $task = Task::create([
            'category_id' => $request->category_id,
            'performer' => $request->performer,
            'title' => $request->title,
            'period' => $request->period,
            'file' => $filePath, 
        ]);
        
        $task->areas()->attach($request->area_id);
    
        return redirect('/tasks')->with('success', 'Task created successfully.');
    }
      
    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $areas = Area::all();
        $categories = Category::all();
        return view('task.task_edit', compact('task', 'categories','areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->all();
    
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('tasks', 'public');
        }
    
        $task->update($data);
    
        if ($request->has('area_id')) {
            $task->areas()->sync($request->input('area_id'));
        }
    
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->file && Storage::exists('public/' . $task->file)) {
            Storage::delete('public/' . $task->file);
        }
    
        $task->delete();
    
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}
