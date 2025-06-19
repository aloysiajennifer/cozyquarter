<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {

        $operationalDays = OperationalDay::orderBy('date')->get();


        $selectedDate = $request->input('date');
        $selectedCwspace = $request->input('cwspace'); 
        $cwspaces = Cwspace::all();

        $schedules = [];
        if ($selectedDate) {
            $schedules = Schedule::with(['cwspace', 'time', 'operationalDay'])
                ->whereHas('operationalDay', function ($query) use ($selectedDate) {
                    $query->where('date', $selectedDate);
                })
                ->when($selectedCwspace, function ($query) use ($selectedCwspace) {
                    $query->where('id_cwspace', $selectedCwspace);
                })
                ->orderBy('id_time')
                ->get();
        }

        $selectedCwspaceObj = null;
        if ($selectedCwspace) {
            $selectedCwspaceObj = Cwspace::find($selectedCwspace);
        }
        return view('admin.schedule.index', compact(
            'operationalDays',
            'selectedDate',
            'schedules',
            'cwspaces',
            'selectedCwspace',
            'selectedCwspaceObj'
        ));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $validated = $request->validate([
            'status_schedule' => 'required|in:0,1,2',
        ]);

        $schedule = Schedule::find($id);
        if (!$schedule) {
            return redirect()->back()->with('error', 'Data is not found.');
        }
        try {
            $schedule->update($validated);
            return redirect()->back()->with('success', 'Schedule status has been successfully updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update schedule status. Please try again due to a database error.');
        }
    }
}
