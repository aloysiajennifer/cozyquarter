<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Beverages;
use App\Models\OrderDetails;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $reservations = Reservation::pluck('id');
        $beverages = Beverages::where('stock','>',0)->get();

        foreach ($reservations as $reservationId) {
            // Mulai dari total = 0
            $total = 0;

            // Simpan dulu Order dengan total sementara 0
            $order = Order::create([
                'total_price' => 0, // akan diupdate nanti
                'status_order' => false,
                'reservation_id' => $reservationId,
            ]);

            // Tambahkan 1-3 item ke order
            $itemCount = rand(1, 3);
            for ($i = 0; $i < $itemCount; $i++) {
                $beverage = $beverages->random();
                $quantity = rand(1, 5);
                $subtotal = $beverage->price * $quantity;

                // Buat OrderDetails
                OrderDetails::create([
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'order_id' => $order->id,
                    'beverage_id' => $beverage->id,
                ]);

                $total += $subtotal;
            }

            // Update total_price setelah semua details ditambahkan
            $order->update([
                'total_price' => $total,
            ]);
        }
    }
}
