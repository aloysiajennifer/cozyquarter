<?php

namespace App\Http\Controllers;

use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    
    public function orderdetails()
    {
        $orders = Order::with('orderDetails.beverage')->get();

        return view('user.yourOrder', compact('orders'));
    }

    public function index()
    {
        $orders = Order::with('orderdetails')->get();

        return view('admin.order.orderIndex', [
            'orders' => $orders
        ]);
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        $order->status_order = 1;
        $order->save();

        return redirect()->back()->with('success', 'Order marked as paid.');
    }
}
