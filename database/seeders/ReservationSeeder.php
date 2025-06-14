<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Cwspace;
use App\Models\Time;
use App\Models\OperationalDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Don't forget to import Carbon

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Find the 'dummy1' user (assuming UserSeeder has run)
        $dummyUser = User::where('email', 'dummy1@gmail.com')->first();
        if (!$dummyUser) {
            $this->command->warn('dummy1 user not found. Please ensure UserSeeder has been run.');
            return;
        }

        // 2. Find the 'dummyBlacklist' user (assuming UserSeeder has run and set penalty_counter = 2)
        $dummyBlacklistUser = User::where('email', 'dummy2@gmail.com')->first();
        if (!$dummyBlacklistUser) {
            $this->command->warn('dummyBlacklist user not found. Please ensure UserSeeder has been run.');
            return;
        }


        // 3. Get necessary Cwspace and Time IDs
        $cwspaceA = Cwspace::where('code_cwspace', 'A')->first();
        $cwspaceB = Cwspace::where('code_cwspace', 'B')->first();
        $cwspaceC = Cwspace::where('code_cwspace', 'C')->first();

        if (!$cwspaceA || !$cwspaceB || !$cwspaceC) {
            $this->command->warn('Cwspaces A, B, or C not found. Please run CwspaceSeeder first.');
            return;
        }

        $time09_10 = Time::where('start_time', '09:00:00')->first();
        $time10_11 = Time::where('start_time', '10:00:00')->first();
        $time11_12 = Time::where('start_time', '11:00:00')->first();
        $time12_13 = Time::where('start_time', '12:00:00')->first();
        $time13_14 = Time::where('start_time', '13:00:00')->first();

        if (!$time09_10 || !$time10_11 || !$time11_12 || !$time12_13 || !$time13_14) {
            $this->command->warn('One or more required time slots not found. Please ensure TimeSeeder has been run and contains these times (09:00, 10:00, 11:00, 12:00, 13:00).');
            return;
        }

        // Define dates
        $historicalDate = Carbon::create(2025, 6, 12)->toDateString(); // Fixed to June 12, 2025
        $operationalDayHistorical = OperationalDay::firstOrCreate(['date' => $historicalDate]);

        $futureDate = Carbon::create(2025, 6, 16)->toDateString(); // Fixed to June 16, 2025
        $operationalDayFuture = OperationalDay::firstOrCreate(['date' => $futureDate]);


        // --- DUMMY1 BOOKINGS ---

        // DUMMY1 Booking 1: Attended (will show as 'Closed' on frontend after Artisan command)
        // June 12, 2025, 10:00 - 11:00 (Closed)
        Reservation::create([
            'status_reservation' => Reservation::STATUS_ATTENDED, // 1
            'reservation_date' => $historicalDate,
            'reservation_start_time' => $time10_11->start_time,
            'reservation_end_time' => $time10_11->end_time,
            'reservation_code_cwspace' => $cwspaceA->code_cwspace,
            'check_in_time' => Carbon::parse($historicalDate . ' ' . $time10_11->start_time)->addMinutes(5),
            'check_out_time' => null,
            'timestamp_reservation' => Carbon::parse($historicalDate)->subDays(1),
            'id_user' => $dummyUser->id,
            'name' => $dummyUser->name,
            'purpose' => 'discussion',
            'email' => $dummyUser->email,
            'num_participants' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Schedule::firstOrCreate(
            ['id_cwspace' => $cwspaceA->id, 'id_operational_day' => $operationalDayHistorical->id, 'id_time' => $time10_11->id,],
            ['status_schedule' => 1, 'id_reservation' => Reservation::latest()->first()->id,] // Link to last created reservation
        );


        // DUMMY1 Booking 2: Reserved (will become Not Attended by Artisan command, incrementing dummy1's penalty_counter to 1)
        // June 12, 2025, 11:00 - 12:00 (Not Attended)
        Reservation::create([
            'status_reservation' => Reservation::STATUS_RESERVED, // Start as Reserved (0)
            'reservation_date' => $historicalDate,
            'reservation_start_time' => $time11_12->start_time,
            'reservation_end_time' => $time11_12->end_time,
            'reservation_code_cwspace' => $cwspaceB->code_cwspace,
            'check_in_time' => null, // Crucially NULL for 'Not Attended'
            'check_out_time' => null,
            'timestamp_reservation' => Carbon::parse($historicalDate)->subDays(1),
            'id_user' => $dummyUser->id,
            'name' => $dummyUser->name,
            'purpose' => 'individual_work',
            'email' => $dummyUser->email,
            'num_participants' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Schedule::firstOrCreate(
            ['id_cwspace' => $cwspaceB->id, 'id_operational_day' => $operationalDayHistorical->id, 'id_time' => $time11_12->id,],
            ['status_schedule' => 1, 'id_reservation' => Reservation::latest()->first()->id,]
        );


        // DUMMY1 Booking 3: Reserved (Future booking)
        // June 16, 2025, 12:00 - 13:00 (Reserved)
        Reservation::create([
            'status_reservation' => Reservation::STATUS_RESERVED, // 0
            'reservation_date' => $futureDate,
            'reservation_start_time' => $time12_13->start_time,
            'reservation_end_time' => $time12_13->end_time,
            'reservation_code_cwspace' => $cwspaceA->code_cwspace,
            'check_in_time' => null,
            'check_out_time' => null,
            'timestamp_reservation' => now(),
            'id_user' => $dummyUser->id,
            'name' => $dummyUser->name,
            'purpose' => 'others',
            'email' => $dummyUser->email,
            'num_participants' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Schedule::firstOrCreate(
            ['id_cwspace' => $cwspaceA->id, 'id_operational_day' => $operationalDayFuture->id, 'id_time' => $time12_13->id,],
            ['status_schedule' => 1, 'id_reservation' => Reservation::latest()->first()->id,]
        );


        // --- DUMMYBLACKIST BOOKINGS (These 3 will trigger blacklisting when Artisan command runs) ---
        // All for June 12, 2025, initially 'Reserved' (0) and will become 'Not Attended' (2) by Artisan command.

        // BLACKLIST Booking 1 (will increment penalty_counter to 1 for dummyBlacklist)
        // June 12, 2025, 09:00 - 10:00 (Not Attended)
        Reservation::create([
            'status_reservation' => Reservation::STATUS_RESERVED, // Start as Reserved (0)
            'reservation_date' => $historicalDate,
            'reservation_start_time' => $time09_10->start_time,
            'reservation_end_time' => $time09_10->end_time,
            'reservation_code_cwspace' => $cwspaceA->code_cwspace, // Room A
            'check_in_time' => null, // No check-in
            'check_out_time' => null,
            'timestamp_reservation' => Carbon::parse($historicalDate)->subDays(1),
            'id_user' => $dummyBlacklistUser->id,
            'name' => $dummyBlacklistUser->name,
            'purpose' => 'individual_work',
            'email' => $dummyBlacklistUser->email,
            'num_participants' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Schedule::firstOrCreate(
            ['id_cwspace' => $cwspaceA->id, 'id_operational_day' => $operationalDayHistorical->id, 'id_time' => $time09_10->id,],
            ['status_schedule' => 1, 'id_reservation' => Reservation::latest()->first()->id,]
        );


        // BLACKLIST Booking 2 (will increment penalty_counter to 2 for dummyBlacklist)
        // June 12, 2025, 10:00 - 11:00 (Not Attended)
        Reservation::create([
            'status_reservation' => Reservation::STATUS_RESERVED, // Start as Reserved (0)
            'reservation_date' => $historicalDate,
            'reservation_start_time' => $time10_11->start_time,
            'reservation_end_time' => $time10_11->end_time,
            'reservation_code_cwspace' => $cwspaceB->code_cwspace, // Room B
            'check_in_time' => null, // No check-in
            'check_out_time' => null,
            'timestamp_reservation' => Carbon::parse($historicalDate)->subDays(1),
            'id_user' => $dummyBlacklistUser->id,
            'name' => $dummyBlacklistUser->name,
            'purpose' => 'discussion',
            'email' => $dummyBlacklistUser->email,
            'num_participants' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Schedule::firstOrCreate(
            ['id_cwspace' => $cwspaceB->id, 'id_operational_day' => $operationalDayHistorical->id, 'id_time' => $time10_11->id,],
            ['status_schedule' => 1, 'id_reservation' => Reservation::latest()->first()->id,]
        );


        // BLACKLIST Booking 3 (will increment penalty_counter to 3, causing blacklist)
        // June 12, 2025, 11:00 - 12:00 (Not Attended)
        $blacklistTriggerReservation = Reservation::create([
            'status_reservation' => Reservation::STATUS_RESERVED, // Start as Reserved (0)
            'reservation_date' => $historicalDate,
            'reservation_start_time' => $time11_12->start_time,
            'reservation_end_time' => $time11_12->end_time,
            'reservation_code_cwspace' => $cwspaceC->code_cwspace, // Room C
            'check_in_time' => null, // No check-in
            'check_out_time' => null,
            'timestamp_reservation' => Carbon::parse($historicalDate)->subDays(1),
            'id_user' => $dummyBlacklistUser->id,
            'name' => $dummyBlacklistUser->name,
            'purpose' => 'others',
            'email' => $dummyBlacklistUser->email,
            'num_participants' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \App\Models\Schedule::firstOrCreate(
            ['id_cwspace' => $cwspaceC->id, 'id_operational_day' => $operationalDayHistorical->id, 'id_time' => $time11_12->id,],
            ['status_schedule' => 1, 'id_reservation' => $blacklistTriggerReservation->id,]
        );
    }
}