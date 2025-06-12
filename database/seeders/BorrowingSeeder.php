<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  borrowing_date  return_due  status_returned return_date id_user id_book
         $data = [
            ['borrowing_date' => '2025-05-01 15:24:39', 'return_due' => '2025-05-08 00:00:00', 'id_user' => 7, 'id_book' => 1],
            ['borrowing_date' => '2025-05-12 13:48:59', 'return_due' => '2025-05-19 00:00:00', 'id_user' => 8, 'id_book' => 2],
            ['borrowing_date' => '2025-05-31 09:12:04', 'return_due' => '2025-06-07 00:00:00', 'id_user' => 9, 'id_book' => 3],
            ['borrowing_date' => '2025-06-06 18:00:17', 'return_due' => '2025-06-13 00:00:00', 'id_user' => 7, 'id_book' => 4],
        ];

        foreach ($data as $item) {
            $borrowing = new Borrowing();
            $borrowing->borrowing_date = $item['borrowing_date'];
            $borrowing->return_due = $item['return_due'];
            $borrowing->id_user = $item['id_user'];
            $borrowing->id_book = $item['id_book'];
            $borrowing->save();

            // Update status_book jadi 0 (unavailable)
            Book::where('id', $item['id_book'])->update(['status_book' => 0]);
        }
    }
}
