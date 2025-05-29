<?php

namespace App\Http\Controllers;

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
        $selectedCwspace = $request->input('cwspace'); // Tambahan

        // Ambil daftar CW Space untuk dropdown filter
        $cwspaces = Cwspace::all(); // Pastikan kamu punya relasi model CWSpace

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

        return view('admin.schedule.index', compact(
            'operationalDays',
            'selectedDate',
            'schedules',
            'cwspaces',
            'selectedCwspace'
        ));
    }

    public function update(Request $request, Schedule $schedule)
    {
        // Validasi input status
        $request->validate([
            'status_schedule' => 'required|in:0,1,2',
        ]);

        // Update status schedule
        $schedule->status_schedule = $request->status_schedule;
        $schedule->save();

        // Redirect kembali ke halaman index dengan filter tanggal yang sama jika ada
        return redirect()->route('schedule.index', ['date' => $schedule->operationalDay->date])
            ->with('success', 'Schedule status updated successfully.');
    }
}
