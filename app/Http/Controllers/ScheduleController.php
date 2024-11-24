<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\ScheduleRequest;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule.index')
            ->with([
                'schedules' => Schedule::all()
            ]);
    }

    public function store(ScheduleRequest $request)
    {
        Schedule::create($request->validated());
        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully');
    }

    public function update(Schedule $schedule, ScheduleRequest $request)
    {
        $schedule->update($request->validated());
        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        if($schedule->movies()->count() > 0){
            return redirect()->route('schedules.index')->with('error', 'Cannot delete this schedule because it has some movies');
        }
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully');
    }
}
