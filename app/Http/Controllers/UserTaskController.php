<?php

namespace App\Http\Controllers;

use App\Models\TaskArea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTaskController extends Controller
{
    public function index()
    {
        if(Auth::user()->area){
            $user = Auth::user()->area->id;

            $taskAreas = TaskArea::where('area_id',$user)->paginate(10);

            return view('user-page.user_page', ['taskAreas' => $taskAreas]);
        }else{
            abort(403);
        }
    }

    public function filterDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = Auth::user();
        
        $taskAreas = TaskArea::whereBetween('period', [$startDate, $endDate])
            ->where('area_id', $user->area->id)->paginate(10);
        return view('user-page.user_page', compact('taskAreas'));
    }
    



    public function takeFilterTask(string $status)
    {
        
        $userAreaId = Auth::user()->area->id;

        switch ($status) {
            case 'all':
                $taskAreas = TaskArea::where('area_id', $userAreaId)->paginate(10);
                break;
            case 'today':
                $taskAreas = TaskArea::where('area_id', $userAreaId)
                    ->whereDate('period', '=', Carbon::today())
                    ->paginate(10);
                break;
            case 'tomorrow':
                $taskAreas = TaskArea::where('area_id', $userAreaId)
                    ->whereDate('period', '=', Carbon::tomorrow())
                    ->paginate(10);
                break;
            case 'twodays':
                $taskAreas = TaskArea::where('area_id', $userAreaId)
                    ->whereBetween('period', [Carbon::today(), Carbon::today()->addDays(2)])
                    ->paginate(10);
                break;
            case 'expired':
                $taskAreas = TaskArea::where('area_id', $userAreaId)
                    ->whereDate('period', '<', Carbon::today())
                    ->paginate(10);
                break;
            default:
                $taskAreas = collect();
        }
        return view('user-page.user_page', compact('taskAreas'));
    }   
}
