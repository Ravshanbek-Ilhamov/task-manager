<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Area;
use App\Models\Category;
use App\Models\Response;
use App\Models\Task;
use App\Models\TaskArea;
use Carbon\Carbon;
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
                
        $taskAreas = TaskArea::paginate(10);
        return view('task.task', compact('taskAreas'));

    }

    public function filterDate(Request $request){

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $taskAreas = TaskArea::whereBetween('period', [$startDate, $endDate])->paginate(10);
        return view('task.task', compact('taskAreas'));
    }    

    public function takeFilterTask(string $status)    {

        switch ($status) {
            case 'all':
                $taskAreas = TaskArea::paginate(10);
                break;
            case 'today':
                $taskAreas = TaskArea::whereDate('period', '=', Carbon::today())
                    ->paginate(10);
                break;
            case 'tomorrow':
                $taskAreas = TaskArea::whereDate('period', '=', Carbon::tomorrow())
                    ->paginate(10);
                break;
            case 'twodays':
                $taskAreas = TaskArea::whereBetween('period', [Carbon::today(), Carbon::today()->addDays(2)])
                    ->paginate(10);
                break;
            case 'expired':
                $taskAreas = TaskArea::whereDate('period', '<', Carbon::today())
                    ->paginate(10);
                break;
            default:
                $taskAreas = collect();
        }
        return view('task.task', compact('taskAreas'));
    }      


    public function open(Request $request, TaskArea $taskArea)    {
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
    
        $regionTasks = collect($request->area_id)->map(function ($area_id) use ($request, $task) {
            return [
                'area_id' => $area_id,
                'task_id' => $task->id,
                'category_id' => $request->category_id,
                'period' => $request->period,
            ];
        });
    
        TaskArea::insert($regionTasks->toArray());
    
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
