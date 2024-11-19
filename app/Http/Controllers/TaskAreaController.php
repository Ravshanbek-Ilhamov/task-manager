<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskAreaRequest;
use App\Http\Requests\UpdateTaskAreaRequest;
use App\Models\Area;
use App\Models\Task;
use App\Models\TaskArea;

class TaskAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        $areas = Area::all();
        $taskAreas = TaskArea::paginate(10);
        return view('taskArea.taskArea',['taskAreas'=>$taskAreas,'tasks' => $tasks,'areas'=>$areas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskAreaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskArea $taskArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskArea $taskArea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskAreaRequest $request, TaskArea $taskArea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskArea $taskArea)
    {
        //
    }
}
