<?php

namespace App\Console\Commands;

use App\Models\OperationalDay;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateOperationalDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operational:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update operational days by adding today and removing older than 14 days';

    /**
     * Execute the console command.
     */
    public function handle() // jalanin pakai -> php artisan operational:update
    {
        $today = Carbon::today();

        // Tambahkan 14 hari ke depan
        for ($i = 0; $i < 14; $i++) {
            $date = $today->copy()->addDays($i);
            OperationalDay::firstOrCreate(['date' => $date]);
        }

        // Hapus yang lebih lama dari hari ini - 1
        $cutoff = $today->copy()->subDay();
        $deleted = OperationalDay::where('date', '<', $cutoff)->delete();

        $this->info("Added 14 operational days from $today");
        $this->info("Deleted records before $cutoff: $deleted");
    }
}
