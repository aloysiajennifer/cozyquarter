<?php

namespace App\Http\Controllers;

use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Schedule;
use App\Models\Schedulel;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {

        $operationalDays = OperationalDay::orderBy('date')->get();


        $selectedDate = $request->input('date');
        $selectedCwspace = $request->input('cwspace'); // Tambahan

        // Ambil daftar CW Space untuk dropdown filter
        $cwspaces = Cwspace::all();

        $schedules = null;
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
            return redirect()->route('schedule.index')->with('error', 'Data tidak ditemukan');
        }
        try {
            $schedule->update($validated);
            return redirect()->route('schedule.index')->with('success', 'Status schedule berhasil di update.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update status schedule. Silakan coba lagi karena kesalahan database.');
        }
    }
}
