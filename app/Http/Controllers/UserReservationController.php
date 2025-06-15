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
    */
   public function index()
   {
       $user = Auth::user();
       if (!$user) {
           return redirect()->route('login')->with('error', 'Please log in to view your reservations.');
       }


       $reservations = Reservation::where('id_user', $user->id)
           ->with(['schedules'])
           ->orderBy('reservation_date', 'desc')
           ->orderBy('reservation_start_time', 'desc')
           ->get();


       $currentTime = Carbon::now();


       // --- Fix: Ensure reservation_date is formatted to date string before concatenation in sortBy ---
       $chronologicalReservations = $reservations->sortBy(function($reservation) {
           // Use toDateString() or format('Y-m-d') to get only the date part, preventing "00:00:00" from reservation_date object
           $datePartForSort = optional($reservation->reservation_date)->toDateString();
           return Carbon::parse($datePartForSort . ' ' . $reservation->reservation_start_time);
       })->values();


       $i = 1;
       foreach ($chronologicalReservations as $chronoRes) {
           $originalRes = $reservations->firstWhere('id', $chronoRes->id);
           if ($originalRes) {
               $originalRes->chronological_number = $i++;
           }
       }


       foreach ($reservations as $reservation) {
           // This part was already correct
           $reservationDateFormatted = $reservation->reservation_date ? $reservation->reservation_date->format('Y-m-d') : $currentTime->format('Y-m-d');
          
           $startTime = $reservation->reservation_start_time ?? '00:00:00';
           $endTime = $reservation->reservation_end_time ?? '00:00:00';


           $bookedDateTimeStart = Carbon::parse($reservationDateFormatted . ' ' . $startTime);
           $bookedDateTimeEnd = Carbon::parse($reservationDateFormatted . ' ' . $endTime);


           $reservation->status_for_display = 'Unknown Status';
           $reservation->show_cancel_button = false;
           $reservation->show_order_drink_button = false;
           $reservation->show_mark_attended_button = false;


           switch ($reservation->status_reservation) {
               case Reservation::STATUS_RESERVED: // 0: Reserved
                   if ($currentTime->lt($bookedDateTimeStart)) {
                       $reservation->status_for_display = 'Reserved';
                       $reservation->show_cancel_button = true;
                   } elseif ($currentTime->gte($bookedDateTimeStart) && $currentTime->lt($bookedDateTimeEnd)) {
                       $reservation->status_for_display = 'Reserved (Active)';
                       $reservation->show_cancel_button = true;
                       $reservation->show_mark_attended_button = true;
                   } else {
                       $reservation->status_for_display = 'Reserved (Overdue)';
                       $reservation->show_cancel_button = true;
                   }
                   break;


               case Reservation::STATUS_ATTENDED: // 1: Attended
                   if ($currentTime->gte($bookedDateTimeEnd->copy()->addMinutes(30))) {
                        $reservation->status_for_display = 'Closed';
                   } elseif ($currentTime->gte($bookedDateTimeStart) && $currentTime->lessThanOrEqualTo($bookedDateTimeEnd->copy()->addMinutes(30))) {
                       $reservation->status_for_display = 'Attended (Active)';
                       $reservation->show_order_drink_button = true;
                   } else {
                       $reservation->status_for_display = 'Attended (Future)';
                   }
                   $reservation->show_cancel_button = false;
                   $reservation->show_mark_attended_button = false;
                   break;


               case Reservation::STATUS_NOT_ATTENDED: // 2: Not Attended
                   $reservation->status_for_display = 'Not Attended';
                   $reservation->show_cancel_button = false;
                   $reservation->show_mark_attended_button = false;
                   $reservation->show_order_drink_button = false;
                   break;


               case Reservation::STATUS_CANCELLED: // 3: Cancelled
                   $reservation->status_for_display = 'Cancelled';
                   $reservation->show_cancel_button = false;
                   $reservation->show_mark_attended_button = false;
                   $reservation->show_order_drink_button = false;
                   break;


               case Reservation::STATUS_CLOSED: // 4: Closed
                   $reservation->status_for_display = 'Closed';
                   $reservation->show_cancel_button = false;
                   $reservation->show_mark_attended_button = false;
                   $reservation->show_order_drink_button = false;
                   break;


               default:
                   $reservation->status_for_display = 'Unknown Status';
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


           if ($reservation->status_reservation !== Reservation::STATUS_RESERVED &&
               $reservation->status_reservation !== Reservation::STATUS_NOT_ATTENDED) {
               throw new \Exception("Reservation cannot be cancelled from its current status.");
           }


           $reservation->status_reservation = Reservation::STATUS_CANCELLED;
           $reservation->save();


           $schedule = Schedule::where('id_reservation', $reservation->id)->first();


           if ($schedule) {
               $reservationEndDateTime = Carbon::parse($reservation->reservation_date->toDateString() . ' ' . $reservation->reservation_end_time);


               // If cancelled before time passes, set schedule status to 1 (Available)
               if (Carbon::now()->lessThan($reservationEndDateTime)) {
                   $schedule->status_schedule = 1; // CHANGED TO 1 (Available, as per GenerateSchedule)
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