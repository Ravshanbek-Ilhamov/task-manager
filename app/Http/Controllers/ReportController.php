<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\TaskArea;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request){

        if(isset( $request->start_date) && isset($request->start_date)){

            $startingDate = $request->start_date;
            $endingDate = $request->end_date;
            
            $categories = Category::with(['taskAreas' => function ($query) use ($startingDate,$endingDate){
                $query->whereHas('tasks',function ($query) use ($startingDate,$endingDate){
                    $query->whereBetween('period',[$startingDate,$endingDate]);
                } );
            }])->get();

        }else{
            $categories = Category::all();
            // $taskAreas = TaskArea::all();
        }


        return view('report.report',[
            'categories' => $categories,
            // 'taskAreas' => $taskAreas,
        ]);
    }

    public function second_report(){
        $categories = Category::all();
        $areas = Area::all();
        return view('report.second_report',['categories' => $categories,'areas'=>$areas]);
    }
}
