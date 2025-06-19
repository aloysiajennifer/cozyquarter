<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User; // Import the User model
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;




class UpdateReservationStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:update-statuses';


    /**
     * The console command description.
     *
     * @var string
     */
    protected /*final*/ $description = 'Updates reservation statuses to Not Attended or Closed based on time.';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Starting reservation status update job.');
        $now = Carbon::now();


        DB::beginTransaction();
        try {
            // Keep track of reservations that just became "Not Attended" in this run
            $justNotAttendedIds = [];


            // 1. Update "Not Attended" (2)
            // Find reservations that are currently 'Reserved' (0),
            // and 20 minutes PAST their reservation_start_time,
            // and they have NOT been checked in.
            $timeThresholdForNotAttended = $now->format('Y-m-d H:i:s');
            
            $notAttendedReservations = Reservation::where('status_reservation', Reservation::STATUS_RESERVED)
                ->whereNull('check_in_time')
                ->whereRaw("CONCAT(reservation_date, ' ', reservation_start_time) + INTERVAL 20 MINUTE <= ?", [$timeThresholdForNotAttended])
                ->get();


            foreach ($notAttendedReservations as $reservation) {
                $reservation->status_reservation = Reservation::STATUS_NOT_ATTENDED;
                $reservation->save();
                $justNotAttendedIds[] = $reservation->id;
                Log::info("Reservation #{$reservation->id} marked as Not Attended (Auto).");


                // --- Logic to update penalty_counter ---
                $user = User::find($reservation->id_user);
                if ($user) {
                    $user->increment('penalty_counter');
                    Log::info("User {$user->email}'s penalty_counter incremented to {$user->penalty_counter}.");


                    if ($user->isBlacklisted()) {
                        $this->info("User {$user->email} (ID: {$user->id}) is now blacklisted (penalty_counter: {$user->penalty_counter})!");
                        Log::warning("User {$user->email} has reached blacklist threshold.");
                    }
                }
            }


            // 2. Update "Closed" (4)
            // Find reservations whose end time has passed, and are NOT:
            // - Already Cancelled (3)
            // - Already Closed (4)
            // - And also NOT Not Attended (2)
            // - OR were just marked as Not Attended in THIS current run (this line is not strictly needed after adding explicit exclusion, but harmless)
            $closedReservationsQuery = Reservation::query()
                ->where('status_reservation', '!=', Reservation::STATUS_CANCELLED)
                ->where('status_reservation', '!=', Reservation::STATUS_CLOSED)
                ->where('status_reservation', '!=', Reservation::STATUS_NOT_ATTENDED) // <--- ADD THIS LINE
                ->whereRaw("CONCAT(reservation_date, ' ', reservation_end_time) <= ?", [$now->format('Y-m-d H:i:s')]);


            // This next block is now redundant for excluding Not Attended if the above line is added,
            // but it's specifically for *this current run's* newly marked Not Attended reservations.
            // Keeping it doesn't hurt, it just ensures immediate consistency if the cron runs very frequently.
            if (!empty($justNotAttendedIds)) {
                $closedReservationsQuery->whereNotIn('id', $justNotAttendedIds);
            }


            $closedReservations = $closedReservationsQuery->get();


            foreach ($closedReservations as $reservation) {
                $reservation->status_reservation = Reservation::STATUS_CLOSED;
                $reservation->save();
                Log::info("Reservation #{$reservation->id} marked as Closed (Auto).");
            }


            DB::commit();
            $this->info('Reservation statuses and user penalty counters updated successfully!');


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in UpdateReservationStatuses command: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->error('Error updating reservation statuses: ' . $e->getMessage());
            return Command::FAILURE;
        }


        Log::info('Reservation status update job finished.');
        return Command::SUCCESS;
    }
}