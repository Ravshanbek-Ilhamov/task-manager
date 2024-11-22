<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view('report.report',[
            'categories' => $categories,
        ]);
    }
}
