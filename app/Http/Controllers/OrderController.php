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
}
