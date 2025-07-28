<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function show($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        $orderitems = OrderItem::where('order_id', $order->id)->get();
        $paymentStatus = $order->payment_status; // obtener el valor de payment_status de la orden
           $commission = $order->commission;
        return view('OrderSummary', compact('order', 'paymentStatus','orderitems','commission'));
        //return view('OrderSummary', compact('order', 'orderTickets','paymentStatus'));
    }
    
    
       
    
}