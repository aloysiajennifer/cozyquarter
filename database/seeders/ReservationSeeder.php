<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Schedule;
use App\Models\OperationalDay;
use App\Models\Time;
use App\Models\Cwspace;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role_id', '1')->inRandomOrder()->limit(5)->get();

        // Jika user kurang dari 5, buat sisanya
        if ($users->count() < 5) {
            $this->command->warn('Jumlah user dengan role_id 1 kurang dari 5, membuat user baru...');
            $usersToCreate = 5 - $users->count();
            User::factory()->count($usersToCreate)->create(['role_id' => '1']);
            // Ambil lagi user setelah dibuat
            $users = User::where('role_id', '1')->inRandomOrder()->limit(5)->get();
        }
        $now = Carbon::now();
        // --- Definisi Status ---
        // null = Pending (belum waktunya)
        // 0    = Not Attended (lewat waktu, tidak datang)
        // 1    = Attended (datang, check-in)
        // 2    = Cancelled (dibatalkan)
        // 3    = Closed (selesai, check-out)

        // --- Reservasi 1: Pending (Masa Depan) - Status NULL ---
        $schedule_pending = Schedule::where('status_schedule', 1)
            ->whereNull('id_reservation')
            ->whereHas('operationalDay', function ($q) use ($now) {
                $q->where('date', '>', $now->toDateString()); // Di masa depan
            })
            ->inRandomOrder()->first();


        $reservation = Reservation::create([
            'id_user' => $users[0]->id,
            'reservation_code_cwspace' => $schedule_pending->cwspace->code_cwspace,
            'reservation_date' => $schedule_pending->operationalDay->date,
            'reservation_start_time' => $schedule_pending->time->start_time,
            'reservation_end_time' => $schedule_pending->time->end_time,
            'status_reservation' => null,
            'timestamp_reservation' => Carbon::now()->subDays(rand(1, 2)),
        ]);
        $schedule_pending->update(['id_reservation' => $reservation->id, 'status_schedule' => 2]);

        // --- Reservasi 2: Not Attended - Status 0 ---
        // Bikin jadwal baru untuk yg di datang di masa lalu krn schedule cuma simpan 2 minggu ke depan
        $this->createHardcodedReservation($users[1], 0, 'past');

        // --- Reservasi 3: Attended - Status 1 ---
        // Mencari jadwal untuk hari ini
        $schedule_attended = Schedule::where('status_schedule', 1)
            ->whereNull('id_reservation')
            ->whereHas('operationalDay', function ($q) use ($now) {
                $q->where('date', $now->toDateString()); // harus hari ini
            })
            ->whereHas('time', function ($q) use ($now) {
                $q->where('start_time', '<=', $now->toTimeString()); // Waktu mulai sudah lewat
            })
            ->inRandomOrder()->first();

        if ($schedule_attended) {
            $checkInTime = Carbon::parse($schedule_attended->operationalDay->date . ' ' . $schedule_attended->time->start_time)->addMinutes(rand(5, 15));
            if ($checkInTime->isFuture()) {
                $checkInTime = Carbon::now()->subMinutes(rand(1, 10));
            }

            $reservation = Reservation::create([
                'id_user' => $users[2]->id,
                'reservation_code_cwspace' => $schedule_attended->cwspace->code_cwspace,
                'reservation_date' => $schedule_attended->operationalDay->date,
                'reservation_start_time' => $schedule_attended->time->start_time,
                'reservation_end_time' => $schedule_attended->time->end_time,
                'status_reservation' => 1,
                'check_in_time' => $checkInTime,
                'timestamp_reservation' => $checkInTime->copy()->subHours(rand(1, 24)),
            ]);
            $schedule_attended->update(['id_reservation' => $reservation->id, 'status_schedule' => 2]);
        } else {
            $this->createHardcodedReservation($users[2], 1, 'today');
        }

        // --- Reservasi 4: Closed - Status 3 ---
        $this->createHardcodedReservation($users[3], 3, 'past');

        // --- Reservasi 5: Cancelled - Status 2 ---
        // Reservasi dibatalkan di untuk jadwal masa depan
        $schedule_to_cancel = Schedule::where('status_schedule', 1)
            ->whereNull('id_reservation')
            ->whereHas('operationalDay', function ($q) use ($now) {
                $q->where('date', '>', $now->toDateString()); // di masa depan
            })
            ->inRandomOrder()->first();


        $reservation = Reservation::create([
            'id_user' => $users[4]->id,
            'reservation_code_cwspace' => $schedule_to_cancel->cwspace->code_cwspace,
            'reservation_date' => $schedule_to_cancel->operationalDay->date,
            'reservation_start_time' => $schedule_to_cancel->time->start_time,
            'reservation_end_time' => $schedule_to_cancel->time->end_time,
            'status_reservation' => 2,
            'timestamp_reservation' => Carbon::now()->subHours(rand(1, 24)), // Waktu pembatalan
        ]);
    }


    protected function createHardcodedReservation($user, $status_reservation, $dateCondition)
    {
        $cwspace = Cwspace::inRandomOrder()->first() ?? Cwspace::factory()->create();
        $time = Time::inRandomOrder()->first() ?? Time::factory()->create(['start_time' => '09:00:00', 'end_time' => '10:00:00']);

        $reservationDate = Carbon::now();
        if ($dateCondition === 'future') {
            $reservationDate = Carbon::now()->addDays(rand(3, 10));
        } elseif ($dateCondition === 'past') {
            $reservationDate = Carbon::now()->subDays(rand(3, 10));
        }

        $reservationDateStr = $reservationDate->toDateString();

        $data = [
            'id_user' => $user->id,
            'reservation_code_cwspace' => $cwspace->code_cwspace,
            'reservation_date' => $reservationDateStr,
            'reservation_start_time' => $time->start_time,
            'reservation_end_time' => $time->end_time,
            'status_reservation' => $status_reservation,
            'check_in_time' => null,
            'check_out_time' => null,
            'timestamp_reservation' => Carbon::now()->subDays(1),
        ];

        $actualStartTime = Carbon::parse($reservationDateStr . ' ' . $time->start_time);
        $actualEndTime = Carbon::parse($reservationDateStr . ' ' . $time->end_time);

        switch ($status_reservation) {
            case 1: // Attended
                $data['check_in_time'] = $actualStartTime->addMinutes(rand(5, 15));
                if ($dateCondition === 'today' && $data['check_in_time']->isFuture()) {
                    $data['check_in_time'] = Carbon::now()->subMinutes(rand(1, 10));
                }
                $data['timestamp_reservation'] = $actualStartTime->subHours(rand(1, 24));
                break;
            case 3: // Closed
                $data['check_in_time'] = $actualStartTime->addMinutes(rand(0, 5));
                $data['check_out_time'] = $actualEndTime->subMinutes(rand(0, 5));
                $data['timestamp_reservation'] = $actualStartTime->subDays(rand(2, 8));
                break;
            case 0: // Not Attended
            case 2: // Cancelled
            case null: // Pending
                $data['timestamp_reservation'] = $actualStartTime->subHours(rand(1, 48));
                break;
        }

        $reservation = Reservation::create($data);

        // Hanya update schedule jika statusnya bukan Cancelled
        if ($status_reservation != 2) {
            $operationalDay = OperationalDay::firstOrCreate(['date' => $reservationDateStr]);

            // Buat schedule yang terikat dengan reservasi ini
            $schedule = Schedule::firstOrCreate(
                [
                    'id_operational_day' => $operationalDay->id,
                    'id_time' => $time->id,
                    'id_cwspace' => $cwspace->id,
                ],
                [
                    'status_schedule' => 2, // Reserved
                    'id_reservation' => $reservation->id,
                ]
            );

            // Jika schedule sudah ada sebelumnya tapi belum terisi, update
            if ($schedule->wasRecentlyCreated === false && $schedule->id_reservation === null) {
                $schedule->update([
                    'status_schedule' => 2,
                    'id_reservation' => $reservation->id,
                ]);
            }
        }
    }
}
