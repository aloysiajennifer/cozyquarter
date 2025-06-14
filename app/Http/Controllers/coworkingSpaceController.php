<?php

namespace App\Http\Controllers;

use App\Models\Cwspace;
use App\Models\OperationalDay;
use App\Models\Schedule;
use App\Models\Time;
use App\Models\Reservation; // Ensure the Reservation model is correctly imported
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException; // For explicit validation exceptions
use Illuminate\Support\Facades\Log; // For logging errors and informational messages
use Illuminate\Support\Facades\DB; // For database transactions
use Illuminate\Support\Facades\Auth; // For authenticating users
use App\Models\User; // Import User model to check blacklist status

class CoworkingSpaceController extends Controller
{
    /**
     * Display the coworking space schedule for a given date.
     * This method fetches available coworking spaces and their booking statuses
     * for a user-selected date, within a specific range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSchedule(Request $request)
    {
        // Get the date from the request or default to today's date
        $date = $request->input('date', Carbon::now()->toDateString());
        $carbonDate = Carbon::parse($date);

        // --- Server-side validation for date range ---
        // Booking is allowed only from today up to 2 weeks from now.
        $twoWeeksFromNow = Carbon::now()->addWeeks(2)->endOfDay();
        if ($carbonDate->isBefore(Carbon::now()->startOfDay()) || $carbonDate->isAfter($twoWeeksFromNow)) {
            // Redirect to today's date if selected date is outside the valid range
            return redirect()->route('coworking.schedule', ['date' => Carbon::now()->toDateString()])
                             ->with('error', 'Selected date is outside the valid booking range (today to 2 weeks).');
        }

        // --- Server-side validation for weekends ---
        if ($carbonDate->isWeekend()) {
            // Find the next available weekday from the current date (or selected date)
            $nextWeekday = $carbonDate->copy();
            do {
                $nextWeekday->addDay();
            } while ($nextWeekday->isWeekend());

            // Redirect to the next available weekday with an informative message
            return redirect()->route('coworking.schedule', ['date' => $nextWeekday->toDateString()])
                             ->with('error', 'Weekends (Saturday and Sunday) are not available for booking. Displaying schedule for ' . $nextWeekday->format('F d, Y') . '.');
        }

        // Fetch all available coworking spaces from the database
        $cwspaces = Cwspace::all();

        // Define the fixed time slots for booking.
        // These labels are used for display in the frontend.
        $timeSlots = [
            ['start' => '09:00:00', 'end' => '10:00:00', 'label' => '09.00 - 10.00'],
            ['start' => '10:00:00', 'end' => '11:00:00', 'label' => '10.00 - 11.00'],
            ['start' => '11:00:00', 'end' => '12:00:00', 'label' => '11.00 - 12.00'],
            ['start' => '12:00:00', 'end' => '13:00:00', 'label' => '12.00 - 13.00'],
            ['start' => '13:00:00', 'end' => '14:00:00', 'label' => '13.00 - 14.00'],
            ['start' => '14:00:00', 'end' => '15:00:00', 'label' => '14.00 - 15.00'],
            ['start' => '15:00:00', 'end' => '16:00:00', 'label' => '15.00 - 16.00'],
            ['start' => '16:00:00', 'end' => '17:00:00', 'label' => '16.00 - 17.00'],
            ['start' => '17:00:00', 'end' => '18:00:00', 'label' => '17.00 - 18.00'],
            ['start' => '18:00:00', 'end' => '19:00:00', 'label' => '18.00 - 19.00'],
            ['start' => '19:00:00', 'end' => '20:00:00', 'label' => '19.00 - 20.00'],
        ];

        // Find or create the operational day record for the selected date.
        $operationalDay = OperationalDay::firstOrCreate(['date' => $carbonDate->toDateString()]);

        // Fetch all schedule entries for the specific operational day.
        $scheduleEntries = Schedule::with(['time', 'cwspace', 'reservation'])
                                    ->where('id_operational_day', $operationalDay->id)
                                    ->get()
                                    ->keyBy(function($item) {
                                        return $item->id_cwspace . '-' . $item->time->start_time;
                                    });

        // Initialize an array to hold the processed schedule statuses for the view.
        $schedules = [];
        foreach ($cwspaces as $space) {
            foreach ($timeSlots as $slot) {
                $status = 'available';
                $key = $space->id . '-' . $slot['start'];
                $scheduleEntry = $scheduleEntries->get($key);

                if ($scheduleEntry) {
                    switch ($scheduleEntry->status_schedule) {
                        case 0:
                            $status = 'closed';
                            break;
                        case 1:
                            if ($scheduleEntry->id_reservation && $scheduleEntry->reservation) {
                                $reservationStatus = $scheduleEntry->reservation->status_reservation;
                                $now = Carbon::now();
                                $reservationEndDateTime = Carbon::parse($scheduleEntry->reservation->reservation_date->toDateString() . ' ' . $scheduleEntry->reservation->reservation_end_time);

                                if ($reservationStatus === Reservation::STATUS_CANCELLED && $now->lessThan($reservationEndDateTime)) {
                                    $status = 'available';
                                } elseif ($reservationStatus === Reservation::STATUS_RESERVED && $now->lessThan($reservationEndDateTime)) {
                                    $status = 'reserved';
                                } elseif (in_array($reservationStatus, [
                                    Reservation::STATUS_ATTENDED,
                                    Reservation::STATUS_NOT_ATTENDED,
                                    Reservation::STATUS_CLOSED
                                ])) {
                                    $status = 'reserved';
                                } else {
                                    $status = 'reserved';
                                }
                            } else {
                                $status = 'reserved';
                            }
                            break;
                        case 2:
                        default:
                            $status = 'available';
                            break;
                    }
                }
                $schedules[$space->id][$slot['start']] = $status;
            }
        }

        $userName = Auth::check() ? Auth::user()->name : '';
        $userEmail = Auth::check() ? Auth::user()->email : '';

        $userActiveReservations = [];
        if (Auth::check()) {
            $userActiveReservations = Reservation::where('id_user', Auth::id())
                ->where('reservation_date', $carbonDate->toDateString())
                ->whereIn('status_reservation', [
                    Reservation::STATUS_RESERVED,
                    Reservation::STATUS_ATTENDED
                ])
                ->select('reservation_date', 'reservation_start_time', 'reservation_end_time')
                ->get()
                ->toArray();
        }

        return view('user.library.coworkingSpace', compact('cwspaces', 'timeSlots', 'schedules', 'date', 'userName', 'userEmail', 'userActiveReservations'));
    }

    /**
     * Handles the reservation booking request.
     */
    public function storeReservation(Request $request)
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();
            if (is_null($userId)) {
                throw new \Exception('User must be logged in to make a reservation. Please log in or register.');
            }

            // --- Blacklist Check: Uses isBlacklisted() helper on User model ---
            $user = Auth::user();
            if ($user && $user->isBlacklisted()) { // This now checks penalty_counter >= 3
                throw ValidationException::withMessages(['general' => 'You are blacklisted from making a new booking due to repeated no-shows.']);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'purpose' => 'required|string|in:individual_work,discussion,others',
                'email' => 'required|email|max:255',
                'numParticipants' => 'required|integer|min:1',
                'bookingDate' => 'required|date_format:Y-m-d|after_or_equal:today',
                'selectedTimeSlots' => 'required|array|min:1|max:1',
                'selectedTimeSlots.*.roomId' => 'required|exists:cwspaces,id',
                'selectedTimeSlots.*.timeStart' => 'required|string|date_format:H:i:s',
                'selectedTimeSlots.*.timeLabel' => 'required|string',
            ]);

            $name = $validatedData['name'];
            $purpose = $validatedData['purpose'];
            $email = $validatedData['email'];
            $numParticipants = $validatedData['numParticipants'];
            $bookingDate = Carbon::parse($validatedData['bookingDate']);
            $selectedSlot = $validatedData['selectedTimeSlots'][0];

            $twoWeeksFromNow = Carbon::now()->addWeeks(2)->endOfDay();
            if ($bookingDate->isBefore(Carbon::now()->startOfDay()) || $bookingDate->isAfter($twoWeeksFromNow)) {
                throw ValidationException::withMessages(['bookingDate' => 'Selected date is outside the valid booking range (today to 2 weeks).']);
            }

            if ($bookingDate->isWeekend()) {
                throw ValidationException::withMessages(['bookingDate' => 'Weekends (Saturday and Sunday) are not available for booking. Please select a weekday.']);
            }

            if ($purpose === 'individual_work' && $numParticipants !== 1) {
                throw ValidationException::withMessages(['numParticipants' => 'For individual work, the number of participants must be 1.']);
            } elseif ($purpose === 'discussion' && $numParticipants <= 1) {
                throw ValidationException::withMessages(['numParticipants' => 'For discussion, the number of participants must be greater than 1.']);
            }

            $bookedRoomId = $selectedSlot['roomId'];
            $timeStart = $selectedSlot['timeStart'];
            $timeLabel = $selectedSlot['timeLabel'];

            $bookedCwspace = Cwspace::find($bookedRoomId);
            if (!$bookedCwspace) {
                throw new \Exception('Invalid room selected for reservation.');
            }
            if ($numParticipants > $bookedCwspace->capacity_cwspace) {
                throw ValidationException::withMessages(['numParticipants' => "Number of participants ({$numParticipants}) exceeds the capacity of Room {$bookedCwspace->code_cwspace} (Capacity: {$bookedCwspace->capacity_cwspace})."]);
            }

            $existingReservationAtTime = Reservation::where('id_user', $userId)
                ->where('reservation_date', $bookingDate->toDateString())
                ->where('reservation_start_time', $timeStart)
                ->whereIn('status_reservation', [
                    Reservation::STATUS_RESERVED,
                    Reservation::STATUS_ATTENDED
                ])
                ->first();

            if ($existingReservationAtTime) {
                $errorMessage = 'You already have an active reservation from ' .
                                Carbon::parse($existingReservationAtTime->reservation_start_time)->format('H:i') . ' to ' .
                                Carbon::parse($existingReservationAtTime->reservation_end_time)->format('H:i') .
                                ' on ' . $bookingDate->format('M d, Y') .
                                ' in Room ' . $existingReservationAtTime->reservation_code_cwspace .
                                '. Please select a different time slot or date to book another room.';
                throw ValidationException::withMessages(['selectedTimeSlots' => $errorMessage]);
            }

            $operationalDay = OperationalDay::firstOrCreate(['date' => $bookingDate->toDateString()]);

            $timeRecord = Time::where('start_time', $timeStart)->first();
            if (!$timeRecord) {
                throw new \Exception("Time record not found for start time: " . $timeStart . ". Please ensure TimeSeeder has been run and contains this time.");
            }

            $schedule = Schedule::where('id_cwspace', $bookedRoomId)
                ->where('id_operational_day', $operationalDay->id)
                ->where('id_time', $timeRecord->id)
                ->lockForUpdate()
                ->first();

            if (!$schedule) {
                $schedule = Schedule::create([
                    'id_cwspace' => $bookedCwspace->id,
                    'id_operational_day' => $operationalDay->id,
                    'id_time' => $timeRecord->id,
                    'status_schedule' => 2,
                    'id_reservation' => null,
                ]);
                Log::info("Created new schedule entry dynamically for booking: Room {$bookedRoomId}, Time {$timeStart}, Date {$bookingDate->toDateString()}");
            }

            if ($schedule->status_schedule === 1 && $schedule->id_reservation) {
                $existingReservation = Reservation::find($schedule->id_reservation);
                $reservationEndDateTime = Carbon::parse($bookingDate->toDateString() . ' ' . $timeRecord->end_time);

                if ($existingReservation && $existingReservation->status_reservation === Reservation::STATUS_CANCELLED && Carbon::now()->lessThan($reservationEndDateTime)) {
                    $schedule->update(['status_schedule' => 2, 'id_reservation' => null]);
                    Log::info("Re-enabled schedule slot after prior cancellation: Room {$bookedRoomId}, Time {$timeStart}, Date {$bookingDate->toDateString()}");
                } else {
                    throw new \Exception("The slot '{$timeLabel}' in Room '{$bookedCwspace->code_cwspace}' is no longer available. Please select another slot.");
                }
            } elseif ($schedule->status_schedule !== 2) {
                throw new \Exception("The slot '{$timeLabel}' in Room '{$bookedCwspace->code_cwspace}' is no longer available. Please select another slot.");
            }

            $reservation = Reservation::create([
                'status_reservation' => Reservation::STATUS_RESERVED,
                'timestamp_reservation' => Carbon::now(),
                'id_user' => $userId,
                'name' => $name,
                'purpose' => $purpose,
                'email' => $email,
                'num_participants' => $numParticipants,
                'reservation_date' => $bookingDate->toDateString(),
                'reservation_start_time' => $timeRecord->start_time,
                'reservation_end_time' => $timeRecord->end_time,
                'reservation_code_cwspace' => $bookedCwspace->code_cwspace,
                'check_in_time' => null,
                'check_out_time' => null,
            ]);

            $schedule->update([
                'status_schedule' => 1,
                'id_reservation' => $reservation->id,
            ]);

            DB::commit();

            Log::info('New Co-working Space Reservation confirmed:', [
                'reservation_id' => $reservation->id,
                'name' => $name,
                'email' => $email,
                'bookingDate' => $bookingDate->toDateString(),
                'selectedSlot' => $timeLabel . ' in Room ' . $bookedCwspace->code_cwspace,
                'user_id' => $userId,
                'status_reservation' => 'Reserved',
            ]);

            $displaySelectedTimeSlots = [$timeLabel . ' (Room ' . $bookedCwspace->code_cwspace . ')'];

            return response()->json([
                'message' => 'Reservation successfully confirmed!',
                'bookingDetails' => [
                    'name' => $name,
                    'purpose' => $purpose,
                    'email' => $email,
                    'numParticipants' => $numParticipants,
                    'bookingDate' => $bookingDate->toDateString(),
                    'selectedTimeSlots' => $displaySelectedTimeSlots,
                ]
            ], 200);

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation Error during reservation:', ['errors' => $e->errors(), 'request_data' => $request->all()]);
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General Error storing reservation: ' . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request_data' => $request->all()]);
            return response()->json([
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}