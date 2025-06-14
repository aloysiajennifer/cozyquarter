<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\Time;
use App\Models\Cwspace;
use App\Models\OperationalDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class UserReservationController extends Controller
{
    /**
     * Display a list of the authenticated user's reservations.
     * Reservations are sorted by reservation_date (ascending) and then reservation_start_time (ascending).
     * This will make the earliest booking appear as Reservation #1.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your reservations.');
        }

        $reservations = Reservation::where('id_user', $user->id)
            ->with(['schedules'])
            ->orderBy('reservation_date', 'asc')       // <--- CHANGED TO ASC
            ->orderBy('reservation_start_time', 'asc') // <--- CHANGED TO ASC
            ->get();

        $currentTime = Carbon::now();

        foreach ($reservations as $reservation) {
            $datePart = optional($reservation->reservation_date)->toDateString() ?? $currentTime->toDateString();
            $startTime = $reservation->reservation_start_time ?? '00:00:00';
            $endTime = $reservation->reservation_end_time ?? '00:00:00';

            $bookedDateTimeStart = Carbon::parse($datePart . ' ' . $startTime);
            $bookedDateTimeEnd = Carbon::parse($datePart . ' ' . $endTime);

            $reservation->status_for_display = 'Unknown Status';
            $reservation->show_cancel_button = false;
            $reservation->show_order_drink_button = false;
            $reservation->show_mark_attended_button = false;

            switch ($reservation->status_reservation) {
                case Reservation::STATUS_RESERVED: // 0: Reserved
                    if ($currentTime->lt($bookedDateTimeStart)) {
                        $reservation->status_for_display = 'Reserved'; // Future booking
                        $reservation->show_cancel_button = true;
                    } elseif ($currentTime->gte($bookedDateTimeStart) && $currentTime->lt($bookedDateTimeEnd)) {
                        $reservation->status_for_display = 'Reserved (Active)'; // Currently within booking time
                        $reservation->show_cancel_button = true;
                        $reservation->show_mark_attended_button = true; // User can check-in
                    } else {
                        // If it's still STATUS_RESERVED (0) but time has passed,
                        // this means the Artisan command hasn't run yet or failed to process it.
                        // Display as 'Overdue' and allow cancellation for cleanup.
                        $reservation->status_for_display = 'Reserved (Overdue)';
                        $reservation->show_cancel_button = true;
                    }
                    break;

                case Reservation::STATUS_ATTENDED: // 1: Attended
                    // If Attended, and the booking time (plus grace period) has passed, display as 'Closed'.
                    // Otherwise, if active, display as 'Attended (Active)' with Order Drink button.
                    if ($currentTime->gte($bookedDateTimeEnd->copy()->addMinutes(30))) { // 30 min grace period after end time
                         $reservation->status_for_display = 'Closed'; // Display as Closed
                         // No buttons for closed state.
                    } elseif ($currentTime->gte($bookedDateTimeStart) && $currentTime->lessThanOrEqualTo($bookedDateTimeEnd->copy()->addMinutes(30))) {
                        // Attended and currently active/within grace period
                        $reservation->status_for_display = 'Attended (Active)';
                        $reservation->show_order_drink_button = true;
                    } else {
                        // Attended but time hasn't started yet (unlikely to happen with correct flow)
                        $reservation->status_for_display = 'Attended (Future)'; // Fallback for very early attended marking
                    }
                    // No cancel or mark attended buttons for Attended state.
                    $reservation->show_cancel_button = false;
                    $reservation->show_mark_attended_button = false;
                    break;

                case Reservation::STATUS_NOT_ATTENDED: // 2: Not Attended
                    $reservation->status_for_display = 'Not Attended';
                    // NO BUTTONS: User requested no action buttons for 'Not Attended' status.
                    $reservation->show_cancel_button = false;
                    $reservation->show_mark_attended_button = false;
                    $reservation->show_order_drink_button = false;
                    break;

                case Reservation::STATUS_CANCELLED: // 3: Cancelled
                    $reservation->status_for_display = 'Cancelled';
                    // No buttons
                    $reservation->show_cancel_button = false;
                    $reservation->show_mark_attended_button = false;
                    $reservation->show_order_drink_button = false;
                    break;

                case Reservation::STATUS_CLOSED: // 4: Closed
                    $reservation->status_for_display = 'Closed';
                    // No buttons
                    $reservation->show_cancel_button = false;
                    $reservation->show_mark_attended_button = false;
                    $reservation->show_order_drink_button = false;
                    break;

                default:
                    $reservation->status_for_display = 'Unknown Status';
                    // No buttons
                    $reservation->show_cancel_button = false;
                    $reservation->show_mark_attended_button = false;
                    $reservation->show_order_drink_button = false;
                    break;
            }
        }

        return view('user.library.yourReservation', compact('reservations'));
    }

    /**
     * Handle cancellation of a user's reservation.
     */
    public function cancelReservation(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user) {
                throw new \Exception('Authentication required to cancel reservation.');
            }

            $reservation = Reservation::where('id', $id)
                                    ->where('id_user', $user->id)
                                    ->with(['schedules'])
                                    ->lockForUpdate()
                                    ->first();

            if (!$reservation) {
                throw new \Exception('Reservation not found or does not belong to you.');
            }

            $nonCancellableStatuses = [
                Reservation::STATUS_ATTENDED,
                Reservation::STATUS_CLOSED,
                Reservation::STATUS_CANCELLED
            ];

            if (in_array($reservation->status_reservation, $nonCancellableStatuses)) {
                $statusName = '';
                switch ($reservation->status_reservation) {
                    case Reservation::STATUS_ATTENDED: $statusName = 'attended'; break;
                    case Reservation::STATUS_CLOSED: $statusName = 'closed'; break;
                    case Reservation::STATUS_CANCELLED: $statusName = 'already cancelled'; break;
                }
                throw new \Exception("Cannot cancel a reservation that is {$statusName}.");
            }

            // Allowed to cancel if status is Reserved (0) or Not Attended (2)
            if ($reservation->status_reservation !== Reservation::STATUS_RESERVED &&
                $reservation->status_reservation !== Reservation::STATUS_NOT_ATTENDED) {
                throw new \Exception("Reservation cannot be cancelled from its current status.");
            }

            $reservation->status_reservation = Reservation::STATUS_CANCELLED;
            $reservation->save();

            $schedule = Schedule::where('id_reservation', $reservation->id)->first();

            if ($schedule) {
                $reservationEndDateTime = Carbon::parse($reservation->reservation_date->toDateString() . ' ' . $reservation->reservation_end_time);

                if (Carbon::now()->lessThan($reservationEndDateTime)) {
                    $schedule->status_schedule = 2; // Set to 'available'
                    $schedule->id_reservation = null; // Unlink the reservation
                    $schedule->save();
                    Log::info('Schedule slot released after reservation cancellation:', ['schedule_id' => $schedule->id]);
                }
            } else {
                Log::warning("No schedule entry found for reservation ID: {$reservation->id} during cancellation.");
            }

            DB::commit();
            Log::info("Reservation {$id} cancelled by user {$user->id}.");

            return response()->json(['message' => 'Reservation cancelled successfully!', 'new_status' => 'Cancelled'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error canceling reservation: ' . $e->getMessage(), ['reservation_id' => $id, 'user_id' => Auth::id()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle marking a reservation as attended (User check-in).
     */
    public function markAttended(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user) {
                throw new \Exception('Authentication required to mark reservation as attended.');
            }

            $reservation = Reservation::where('id', $id)
                                    ->where('id_user', $user->id)
                                    ->lockForUpdate()
                                    ->first();

            if (!$reservation) {
                throw new \Exception('Reservation not found or does not belong to you.');
            }

            $datePart = optional($reservation->reservation_date)->toDateString() ?? Carbon::now()->toDateString();
            $startTime = $reservation->reservation_start_time ?? '00:00:00';
            $endTime = $reservation->reservation_end_time ?? '00:00:00';

            $bookedDateTimeStart = Carbon::parse($datePart . ' ' . $startTime);
            $bookedDateTimeEnd = Carbon::parse($datePart . ' ' . $endTime);
            $currentTime = Carbon::now();

            if ($reservation->status_reservation !== Reservation::STATUS_RESERVED) {
                throw new \Exception('Reservation cannot be marked as attended. Current status is not "Reserved".');
            }

            if ($currentTime->lt($bookedDateTimeStart->subMinutes(10)) || $currentTime->gt($bookedDateTimeEnd)) {
                throw new \Exception('You can only mark attendance during or slightly before your reserved time slot.');
            }

            $reservation->status_reservation = Reservation::STATUS_ATTENDED;
            $reservation->check_in_time = $currentTime;
            $reservation->save();

            DB::commit();
            Log::info("Reservation {$id} marked as attended by user {$user->id}.");

            return response()->json(['message' => 'Reservation marked as attended!', 'new_status' => 'Attended'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking reservation as attended: ' . $e->getMessage(), ['reservation_id' => $id, 'user_id' => Auth::id()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Redirect to the beverage menu for ordering.
     */
    public function orderDrink($reservationId)
    {
        $reservation = Reservation::where('id', $reservationId)->where('id_user', Auth::id())->first();
        if (!$reservation || $reservation->status_reservation !== Reservation::STATUS_ATTENDED) {
            return redirect()->back()->with('error', 'You can only order drinks for your active attended reservations.');
        }

        return redirect()->route('beverages.menu', ['reservation_id' => $reservationId]);
    }
}