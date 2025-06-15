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

        $query = Reservation::with(['user']);

        // --- Filter By Reservation Date ---
        if ($request->filled('filter_date')) {
            $filterDate = Carbon::parse($request->input('filter_date'))->toDateString();
            $query->whereDate('reservation_date', $filterDate);
        }

        // --- Filter By CW Space Code ---
        if ($request->filled('filter_cwspace')) {
            $cwspaceId = $request->input('filter_cwspace');
            // Ambil code_cwspace berdasarkan id untuk query reservasi
            $selectedCwspaceForQuery = Cwspace::find($cwspaceId);
            if ($selectedCwspaceForQuery) {
                $query->where('reservation_code_cwspace', $selectedCwspaceForQuery->code_cwspace);
            }
        }

        if ($request->filled('search_user')) {
            $searchTerm = '%' . $request->input('search_user') . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            });
        }

        // Filter berdasarkan Status
        if ($request->filled('filter_status')) {
            $query->where('status_reservation', $request->filter_status);
        }

        $reservations = $query->orderBy('timestamp_reservation', 'desc')->paginate(10);
        $cwspaces = Cwspace::all(); 
        $selectedCwspaceObj = $request->filled('filter_cwspace') 
        ? Cwspace::find($request->input('filter_cwspace')) 
        : null;


        return view('admin.reservation.index', compact('reservations', 'cwspaces', 'selectedCwspaceObj'));
    }

    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $newStatus = $request->input('status');

        // Jika admin mencoba mengubah status menjadi "Attended" (1)
        if ($newStatus == 1 && $reservation->status_reservation == 0) {
            // Ambil HANYA bagian tanggal dari kolom reservation_date
            $dateOnly = Carbon::parse($reservation->reservation_date)->toDateString(); // Hasil: "2025-06-16"

            // Gabungkan tanggal yang sudah bersih dengan waktu mulai
            $reservationStartTime = Carbon::parse($dateOnly . ' ' . $reservation->reservation_start_time);

            // Cek apakah waktu sekarang MASIH SEBELUM waktu reservasi dimulai
            if (Carbon::now()->lt($reservationStartTime)) {
                // Jika ya, kembalikan dengan pesan error spesifik
                return redirect()->back()->with('error', 'Gagal! Waktu check-in untuk reservasi ini belum dimulai.');
            }

            // Jika waktu sudah sesuai, set juga waktu check-in
            $reservation->check_in_time = Carbon::now();
        }

        // Jika admin membatalkan kehadiran (dari 1 ke 0), hapus waktu check-in
        if ($newStatus == 0 && $reservation->status_reservation == 1) {
            $reservation->check_in_time = null;
        }

        $reservation->status_reservation = $newStatus;
        $reservation->save();

        return redirect()->route('reservation.index')->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
