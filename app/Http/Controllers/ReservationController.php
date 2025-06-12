<?php

namespace App\Http\Controllers;

use App\Models\Cwspace;
use App\Models\Reservation;
use App\Models\OperationalDay; // Needed if you plan to use Flatpickr's enable dates
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $cwspaces = Cwspace::where('status_cwspace', 1)->get(['id', 'code_cwspace']);
        // If you're using Flatpickr and need to pass operationalDays for 'enable' dates, uncomment the line below.
        // Otherwise, it's not strictly needed for basic HTML date input.
        // $operationalDays = OperationalDay::all();

        $query = Reservation::with(['user']);

        // --- Filter By Reservation Date ---
        if ($request->filled('filter_date')) {
            $filterDate = Carbon::parse($request->input('filter_date'))->toDateString();
            $query->whereDate('reservation_date', $filterDate);
        }

        // --- Filter By CW Space Code ---
        if ($request->filled('filter_cwspace')) {
            $cwspaceId = $request->input('filter_cwspace');
            $selectedCwspace = Cwspace::find($cwspaceId);
            if ($selectedCwspace) {
                $query->where('reservation_code_cwspace', $selectedCwspace->code_cwspace);
            }
        }

        // --- Search By User Name ---
        if ($request->filled('search_user')) {
            $searchTerm = '%' . $request->input('search_user') . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            });
        }

        $reservations = $query->orderBy('timestamp_reservation', 'desc')->paginate(10);

        // Pass operationalDays if Flatpickr is used; otherwise, just cwspaces
        return view('admin.reservation.index', compact('reservations', 'cwspaces' /*, 'operationalDays' */));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            // Admin can ONLY submit status '0' (Attended)
            'status' => 'required|integer|in:0',
        ]);

        $newStatus = (int) $request->input('status');

        // --- Status Mapping Based on your Migration:
        // NULL: Pending / Belum Jadwalnya
        // 0: Attended
        // 1: Not Attended
        // 2: Cancelled
        // 3: Closed

        // Business Logic: Admin can only mark a 'NULL' reservation as 'Attended' (0).
        if (is_null($reservation->status_reservation)) {
            // Check if the requested new status is indeed Attended (0)
            if ($newStatus === 0) {
                // Optional: You might want to ensure the reservation date is today or in the past
                // before marking it as attended. If it's a future reservation,
                // maybe disallow it or show a specific error.
                $reservationDate = Carbon::parse($reservation->reservation_date);
                if ($reservationDate->isFuture()) {
                    return redirect()->back()->with('error', 'Tidak dapat menandai reservasi di masa depan sebagai Attended secara manual.');
                }

                $reservation->status_reservation = 0; // Change status to Attended
                $reservation->check_in_time = Carbon::now(); // Set check-in time
                $reservation->save();

                return redirect()->back()->with('success', 'Status reservasi berhasil diubah menjadi Attended.');
            } else {
                // This case should ideally not be hit if the button value is strictly '0'
                // but it's a safeguard if someone tries to manipulate the request.
                return redirect()->back()->with('error', 'Aksi tidak valid: hanya bisa mengubah status "Belum Jadwalnya" menjadi "Attended".');
            }
        } else {
            return redirect()->back()->with('error', 'Reservasi ini tidak dapat diubah statusnya melalui aksi ini.');
        }
    }
}