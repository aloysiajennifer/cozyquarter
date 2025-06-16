<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Beverages;
use App\Models\OrderDetails;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::query()
            ->with(['reservation.user', 'reservation.schedule.cwspace', 'orderDetails.beverage'])
            // filter order berdasarkan status_reservation dari reservation
            ->whereHas('reservation', function ($q) {
                $q->whereIn('status_reservation', [1, 4]);
            })
            ->when($search, function ($query, $search) {
                // search filter --> mencari berdasarkan nama user atau code_cwspace
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('reservation.user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('reservation.schedule.cwspace', function ($q) use ($search) {
                            $q->where('code_cwspace', 'like', '%' . $search . '%');
                        });
                });
            })
            // urutkan berdasarkan waktu order terbaru
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.order.orderIndex', compact('orders'));
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        $order->status_order = 1;
        $order->save();

        return redirect()->back()->with('success', 'Order marked as paid.');
    }

    public function yourorder()
    {
        $beverages = Beverages::where('stock', '>', 0)->get();
        return view('user.yourOrder', compact('beverages'));
    }

    public function placeOrder(Request $request)
    {
        $validatedData = $request->validate([
            'order_items' => 'required|array|min:1',
            'order_items.*.id' => 'required|exists:beverages,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $reservation = $user->reservation()->where('status_reservation', 1)->latest()->first();

        if (!$reservation) {
            return response()->json(['message' => 'No active reservation found for this user to place an order.'], 400);
        }

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $itemsForOrderDetails = [];

            // mengambil semua ID beverage dari request untuk query tunggal
            $beverageIds = collect($validatedData['order_items'])->pluck('id')->all();
            // mengambil semua beverage yang relevan sekaligus, dan menguncinya untuk update
            $beverages = Beverages::whereIn('id', $beverageIds)->lockForUpdate()->get()->keyBy('id');

            foreach ($validatedData['order_items'] as $item) {
                $beverage = $beverages->get($item['id']);

                if (!$beverage) {
                    throw ValidationException::withMessages([
                        'order_items' => ['Beverage with ID ' . $item['id'] . ' not found or is no longer available.']
                    ]);
                }

                if ($beverage->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'order_items' => ['Not enough stock for ' . $beverage->name . '. Available: ' . $beverage->stock . ', Requested: ' . $item['quantity'] . '.']
                    ]);
                }

                $subtotal = $beverage->price * $item['quantity'];
                $totalPrice += $subtotal;

                $itemsForOrderDetails[] = [
                    'beverage_id' => $beverage->id,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $beverage->decrement('stock', $item['quantity']);
            }

            // buat Order utama
            $order = Order::create([
                'reservation_id' => $reservation->id,
                'total_price' => $totalPrice,
                'status_order' => 0,
                'order_date' => now(),
            ]);

            // tambahkan order_id ke setiap item detail
            foreach ($itemsForOrderDetails as &$detail) {
                $detail['order_id'] = $order->id;
            }

            OrderDetails::insert($itemsForOrderDetails);

            DB::commit();
            return response()->json(['message' => 'Order placed successfully!', 'order_id' => $order->id], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error placing order: ' . json_encode($e->errors()));
            return response()->json([
                'message' => 'Validation failed for order items.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error placing order: ' . $e->getMessage() . ' at line ' . $e->getLine() . ' in ' . $e->getFile());
            return response()->json(['message' => 'Failed to place order. Please try again.'], 500);
        }
    }
}
