<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Area;
use App\Models\Category;
use App\Models\Response;
use App\Models\Task;
use App\Models\TaskArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $query->whereHas('taskAreas', function ($query) use ($userAreas) {
                $query->whereIn('area_id', $userAreas);
            });
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('period', [$request->start_date, $request->end_date]);
        } 

        $filter = $request->input('filter');
        if ($filter === 'today') {
            $query->whereDate('period', today());
        } elseif ($filter === 'tomorrow') {
            $query->whereDate('period', today()->addDay());
        } elseif ($filter === 'two_days') {
            $query->whereBetween('period', [today(), today()->addDays(2)]);
        } elseif ($filter === 'expired') {
            $query->where('period', '<', today());
        }
        
        $taskCounts = [
            'all' => $query->count(),
            'today' => $query->whereDate('period', today())->count(),
            'tomorrow' => $query->whereDate('period', today()->addDay())->count(),
            'two_days' => $query->whereBetween('period', [today(), today()->addDays(2)])->count(),
            'expired' => $query->where('period', '<', today())->count(),
        ];
        
        $tasks = $query->paginate(10);
        return view('task.task', compact('tasks', 'taskCounts'));
        
    }
    

      
        public function filter($filter){
        // $filter = 'tomorrow';    
        $area_id = Auth::user()->areas->first()->id;

        $all = TaskArea::where('area_id',$area_id)->count();

        $twodays = TaskArea::where('area_id',$area_id)
            ->whereHas('tasks',function($query){
                $query->whereDate('period',now()->addDays(2));
                })->count();
            
        $onedays = TaskArea::where('area_id',$area_id)
                ->whereHas('tasks',function($query){
                    $query->whereDate('period',now()->addDays(1));
                    })->count();

        $todays = TaskArea::where('area_id',$area_id)
                    ->whereHas('tasks',function($query){
                        $query->whereDate('period',now()->addDays(0));
                    })->count();

        $tasks = TaskArea::paginate(100);

        if($filter == 'twodays'){
            $tasks = TaskArea::where('area_id',$area_id)
                ->whereHas('tasks',function($query){
                    $query->whereDate('period',now()->addDays(2));
                })->paginate(100);

        }elseif ($filter == 'tomorrow'){
            $tasks = TaskArea::where('area_id',$area_id)
                ->whereHas('tasks',function($query){
                    $query->whereDate('period',now()->addDays(1));
                })->paginate(100);

        }elseif($filter == 'today'){
            $tasks = TaskArea::where('area_id',$area_id)
                ->whereHas('tasks',function($query){
                    $query->whereDate('period',now()->addDays(0));
                })->paginate(100);
        }

        dd($area_id);
        return view('user-page.user_page',[
            'all'=>$all,
            'twodays'=> $twodays,
            'onedays'=>$onedays,
            'todays'=>$todays,
            'tasks' => $tasks]);
        }    
    
   
        public function open(Request $request, TaskArea $taskArea)
    {
        $taskArea->status = 'opened';
        $taskArea->save();

        return back()->with('success', 'Task status updated to opened!');
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

    public function openTask(TaskArea $taskAreaStatus)
    {
        // Update the status to 'opened'
        $taskAreaStatus->update(['status' => 'opened']);

        return redirect()->back()->with('success', 'Task status updated to "Opened".');
    }

    public function doTask(Request $request, TaskArea $taskAreaStatus)
    {
        $request->validate([
            'note' => 'required|string|max:255',
            'file' => 'nullable|file|max:2048', // Adjust file size as needed
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('responses', 'public');
        }

        // Save the response`
        Response::create([
            'task_id' => $taskAreaStatus->task_id,
            'area_id' => $taskAreaStatus->area_id,
            'title' => $request->input('note'),
            'file' => $filePath,
            'status' => 'done',
        ]);

        // Update the task area status to 'done'
        $taskAreaStatus->update(['status' => 'done']);

        return redirect()->back()->with('success', 'Task successfully marked as "Done".');
    }

    public function response_page(){
        $responses = Response::paginate(10);
        return view('response.response',compact('responses'));
    }

    public function accept($id)
    {
        $response = Response::findOrFail($id);
    
        $response->status = 'approved';
        $response->save();
    
        $taskArea = TaskArea::where('task_id', $response->task_id)
                            ->where('area_id', $response->area_id)
                            ->first();
    
        if ($taskArea) {
            $taskArea->status = 'approved'; // Set the appropriate status
            $taskArea->save();
        }
    
        return redirect()->back()->with('success', 'Response accepted and TaskArea status updated.');
    }
    
    public function rejectWithComment(Request $request)
    {
        $request->validate([
            'response_id' => 'required|exists:responses,id',
            'comment' => 'required|string|max:255',
        ]);
    
        $response = Response::findOrFail($request->response_id);
    
        $response->status = 'rejected';
        $response->comment = $request->comment;
        $response->save();
    
        $taskArea = TaskArea::where('task_id', $response->task_id)
            ->where('area_id', $response->area_id)
            ->first();
    
        if ($taskArea) {
            $taskArea->status = 'sent'; // Set the appropriate status
            $taskArea->save();
        }
    
        return redirect()->back()->with('success', 'Response rejected with a comment, and TaskArea status updated.');
    }
    
    
    

}
