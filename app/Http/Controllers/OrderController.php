<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Beverages;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::query()
            ->when($search, function ($query, $search) {
                // Search filter
                $query->whereHas('reservation.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('reservation.schedule.cwspace', function ($q) use ($search) {
                        $q->where('code_cwspace', 'like', '%' . $search . '%');
                    });
            })
            ->with(['reservation.user', 'reservation.schedule.cwspace', 'orderdetails.beverage'])
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

    public function placeOrder(Request $request) {
        //buat masukkin ke DB
    }
}
