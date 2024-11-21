<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTaskController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();
    
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Query tasks associated with the user's area
        $tasksQuery = Task::whereHas('areas', function ($query) use ($user) {
            $query->where('area_id', $user->area->id);
        });
    
        // Apply date filtering if both dates are provided
        if ($startDate && $endDate) {
            $tasksQuery->where('period', '>=', $startDate)
                       ->where('period', '<=', $endDate);
        }
    
        // Paginate the tasks (10 items per page)
        $tasks = $tasksQuery->paginate(10);
    // dd($tasks);
        return view('user-page.user_page', ['tasks' => $tasks]);
    }
    
    
}
