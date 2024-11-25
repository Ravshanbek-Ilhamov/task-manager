<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $users = User::all();
        $areas = Area::paginate(10);
        return view('area.areas', compact('areas','users'));
    }

    public function store(StoreAreaRequest $request)
    {
        Area::create($request->all());

        return redirect()->back()->with('success', 'Area created successfully!');
    }

    public function update(UpdateAreaRequest $request)
    {

        $area = Area::findOrFail($request->id);
        $area->update($request->all());

        return redirect()->back()->with('success', 'Area updated successfully!');
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return redirect()->back()->with('success', 'Area deleted successfully!');
    }
}
