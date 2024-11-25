<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\TaskArea;
use Carbon\Carbon;

class ControllController extends Controller
{
    public function index(){

        $areas = Area::all(); 
        $categories = Category::all();
        $taskAreas = TaskArea::all();
        $btncolor = 'info';

        $countall = TaskArea::all()->count();
        $twodays = TaskArea::whereBetween('period', [Carbon::today(), Carbon::today()->addDays(2)])->count();
        $tomorrow = TaskArea::whereDate('period', '=', Carbon::tomorrow())->count();
        $today = TaskArea::whereDate('period', '=', Carbon::today())->count();
        $expired = TaskArea::whereDate('period', '<', Carbon::today())->count();


        return view('control.control', compact(
        'areas',
        'categories', 
        'taskAreas',
        'btncolor',
        'countall',
        'twodays',
        'tomorrow',
        'today',
        'expired',
    ));
    }

    public function showTasksByAreaAndCategory($area, $category){
        $taskAreas = TaskArea::where('area_id', $area)
                        ->where('category_id', $category)
                        ->paginate(10);
        return view('task.task', compact('taskAreas'));
    }

    public function filterByStatus(string $status){

        $areas = Area::all(); 
        $categories = Category::all();
        $btncolor = 'info';

        switch ($status) {
            case 'all':
                $taskAreas = TaskArea::all();
                $btncolor = 'info';
                break;

            case 'twodays':
                $taskAreas = TaskArea::whereDate('period', [Carbon::today()->addDays(2)])
                ->where('status' ,'!=', 'approved')
                ->get();
                $btncolor = 'success';
                
                break;
    

            case 'tomorrow':
                $taskAreas = TaskArea::whereDate('period', '=', Carbon::tomorrow())->get();
                $btncolor = 'warning';
               
                break;

            case 'today':
                $taskAreas = TaskArea::whereDate('period', '=', Carbon::today())->get();
                $btncolor = 'danger';

                break;

            case 'expired':
                $taskAreas= TaskArea::whereDate('period', '<', Carbon::today())
                ->where('status' ,'!=', 'approved')
                ->get();
                $btncolor = 'danger';
                
                break;

            default:
                $taskAreas = collect();
        }

        $countall = TaskArea::all()->count();
        $twodays = TaskArea::whereDate('period', [Carbon::today()->addDays(2)])->where('status' ,'!=', 'approved')->count();
        $tomorrow = TaskArea::whereDate('period', '=', Carbon::today()->addDays())->where('status' ,'!=', 'approved')->count();
        $today = TaskArea::whereDate('period', '=', Carbon::today())->where('status' ,'!=', 'approved')->count();
        $expired = TaskArea::whereDate('period', '<', Carbon::today())->where('status' ,'!=', 'approved')->count();

        return view('control.control', compact(
        'taskAreas',
        'areas',
        'categories',
        'btncolor',
        'countall',
        'twodays',
        'tomorrow',
        'today',
        'expired',
        ));
    }
}
