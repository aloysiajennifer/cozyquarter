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
use Illuminate\Database\Eloquent\Collection;

class ReservationSeeder extends Seeder
{
    private const STATUS_RESERVED    = 0;
    private const STATUS_ATTENDED    = 1;
    private const STATUS_NOT_ATTENDED = 2;
    private const STATUS_CANCELLED   = 3;
    private const STATUS_CLOSED      = 4;

    public function run(): void
    {
        $this->command->info('Memulai Reservation Seeder...');

        // 1. Siapkan data user
        $users = $this->getOrCreateUsers();
        $now = Carbon::now();

        // 2. Buat satu reservasi untuk setiap status
        $this->createReservedReservation($users[0], $now);
        $this->createNotAttendedReservation($users[1]);
        $this->createAttendedReservation($users[2], $now);
        $this->createClosedReservation($users[3]);
        $this->createCancelledReservation($users[4], $now);

        $this->command->info('Reservation Seeder selesai.');
    }

    /**
     * Membuat reservasi dengan status 'Reserved' (0) untuk jadwal di masa depan.
     */
    private function createReservedReservation(User $user, Carbon $now): void
    {
        $schedule = $this->findAvailableFutureSchedule($now);

        $this->command->info('Membuat reservasi "Reserved"...');
        $reservation = Reservation::create($this->buildReservationData($user, $schedule, self::STATUS_RESERVED));
        $schedule->update(['id_reservation' => $reservation->id, 'status_schedule' => 2]);
    }

    /**
     * Membuat reservasi dengan status 'Not Attended' (2) untuk jadwal di masa lalu.
     */
    private function createNotAttendedReservation(User $user): void
    {
        $this->command->info('Membuat reservasi "Not Attended"...');
        $this->createReservationFromScratch($user, self::STATUS_NOT_ATTENDED, 'past');
    }

    /**
     * Membuat reservasi dengan status 'Attended' (1) jika ada jadwal yang sedang berlangsung.
     */
    private function createAttendedReservation(User $user, Carbon $now): void
    {
        $schedule = Schedule::where('status_schedule', 1)
            ->whereNull('id_reservation')
            ->whereHas('operationalDay', fn($q) => $q->where('date', $now->toDateString()))
            ->whereHas('time', function ($q) use ($now) {
                $q->where('start_time', '<=', $now->toTimeString())
                    ->where('end_time', '>', $now->toTimeString());
            })
            ->inRandomOrder()->first();

        if ($schedule) {
            $this->command->info('Jadwal aktif ditemukan, membuat reservasi "Attended"...');
            $checkInTime = Carbon::parse($schedule->time->start_time)->addMinutes(rand(1, 10));
            $reservationData = $this->buildReservationData($user, $schedule, self::STATUS_ATTENDED, ['check_in_time' => $checkInTime]);
            $reservation = Reservation::create($reservationData);
            $schedule->update(['id_reservation' => $reservation->id, 'status_schedule' => 2]);
        } else {
            $this->command->warn('Tidak ada jadwal aktif untuk "Attended", data tidak dibuat.');
        }
    }

    /**
     * Membuat reservasi dengan status 'Closed' (4) untuk jadwal di masa lalu.
     */
    private function createClosedReservation(User $user): void
    {
        $this->command->info('Membuat reservasi "Closed"...');
        $this->createReservationFromScratch($user, self::STATUS_CLOSED, 'past');
    }

    /**
     * Membuat reservasi dengan status 'Cancelled' (3) untuk jadwal di masa depan.
     */
    private function createCancelledReservation(User $user, Carbon $now): void
    {
        $schedule = $this->findAvailableFutureSchedule($now);
        Reservation::create($this->buildReservationData($user, $schedule, self::STATUS_CANCELLED));
    }


    /**
     * Mengambil atau membuat 5 user dengan role_id 1.
     * Jika kurang dari 5, akan membuat user baru.
     */
    private function getOrCreateUsers(): Collection
    {
        $users = User::where('role_id', '1')->inRandomOrder()->limit(5)->get();
        if ($users->count() < 5) {
            $this->command->warn('Jumlah user (role 1) kurang dari 5, membuat user baru...');
            User::factory()->count(5 - $users->count())->create(['role_id' => '1']);
            return User::where('role_id', '1')->inRandomOrder()->limit(5)->get();
        }
        return $users;
    }

    /**
     * Mencari satu jadwal kosong acak di masa depan.
     */
    private function findAvailableFutureSchedule(Carbon $now): ?Schedule
    {
        return Schedule::where('status_schedule', 1)
            ->whereNull('id_reservation')
            ->whereHas('operationalDay', fn($q) => $q->where('date', '>', $now->toDateString()))
            ->inRandomOrder()->first();
    }

    private function buildReservationData(User $user, Schedule $schedule, int $status, array $overrides = []): array
    {
        return array_merge([
            'id_user' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'purpose' => 'Discussion',
            'reservation_code_cwspace' => $schedule->cwspace->code_cwspace,
            'reservation_date' => $schedule->operationalDay->date,
            'reservation_start_time' => $schedule->time->start_time,
            'reservation_end_time' => $schedule->time->end_time,
            'status_reservation' => $status,
            'num_participants' => rand(1, 3),
            'timestamp_reservation' => Carbon::now()->subDays(rand(1, 5)),
        ], $overrides);
    }

    /**
     * Membuat reservasi dari nol, jika jadwal tidak ditemukan.
     */
    private function createReservationFromScratch(User $user, int $status, string $dateCondition): void
    {
        $cwspace = Cwspace::inRandomOrder()->first() ?? Cwspace::factory()->create();
        $time = Time::inRandomOrder()->first() ?? Time::factory()->create(['start_time' => '09:00:00', 'end_time' => '10:00:00']);

        $reservationDate = match ($dateCondition) {
            'future' => Carbon::now()->addDays(rand(3, 10)),
            'past'   => Carbon::now()->subDays(rand(3, 10)),
            default  => Carbon::now(),
        };
        $reservationDateStr = $reservationDate->toDateString();

        $data = [
            'id_user' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'purpose' => 'Discussion',
            'reservation_code_cwspace' => $cwspace->code_cwspace,
            'reservation_date' => $reservationDateStr,
            'reservation_start_time' => $time->start_time,
            'reservation_end_time' => $time->end_time,
            'status_reservation' => $status,
            'check_in_time' => null,
            'check_out_time' => null,
            'num_participants' => rand(1, 3),
            'timestamp_reservation' => Carbon::now()->subDays(1),
        ];

        $actualStartTime = Carbon::parse($reservationDateStr . ' ' . $time->start_time);
        $actualEndTime = Carbon::parse($reservationDateStr . ' ' . $time->end_time);

        switch ($status) {
            case self::STATUS_ATTENDED:
                $data['check_in_time'] = $actualStartTime->addMinutes(rand(5, 15));
                break;
            case self::STATUS_CLOSED:
                $data['check_in_time'] = $actualStartTime->addMinutes(rand(0, 5));
                $data['check_out_time'] = $actualEndTime->subMinutes(rand(0, 5));
                break;
        }

        $reservation = Reservation::create($data);

        if ($status !== self::STATUS_CANCELLED) {
            $operationalDay = OperationalDay::firstOrCreate(['date' => $reservationDateStr]);
            Schedule::updateOrCreate(
                ['id_operational_day' => $operationalDay->id, 'id_time' => $time->id, 'id_cwspace' => $cwspace->id],
                ['status_schedule' => 2, 'id_reservation' => $reservation->id]
            );
        }
    }
}