<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hall\StoreHallRequest;
use App\Http\Requests\Hall\UpdateHallRequest;
use App\Models\Hall;

class HallController extends Controller
{
    public function index()
    {
        return view('halls.index')->with([
            'halls' => Hall::all()
        ]);
    }

    public function store(StoreHallRequest $request)
    {
        Hall::create($request->validated());
        return back()->with('success', 'Hall created successfully');
    }

    public function show(Hall $hall)
    {
        return view('halls.show')->with([
            'hall' => $hall
        ]);
    }

    public function update(UpdateHallRequest $request, Hall $hall)
    {
        if($hall->movies()->count()){
            return back()->with('error', 'You Can not update this Hall');
        }
        $hall->update($request->validated());
        return back()->with('success', 'Hall updated successfully');
    }
    public function destroy(Hall $hall)
    {
        if($hall->movies()->count()){
            return back()->with('error', 'You Can not delete this Hall');
        }
        $hall->delete();
        return back()->with('success', 'Hall deleted successfully');
    }
}
