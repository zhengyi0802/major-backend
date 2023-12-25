<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function index()
    {
        $orders = Order::where('status', true)->get();

        return view('orders.index', compact('orders'));
    }

    function create()
    {
        return redirect()->route('orders.index');
    }

    function store(Request $request)
    {
        return redirect()->route('orders.index');
    }

    function edit(Order $order)
    {
        return redirect()->route('orders.index');
    }

    function update(Request $request, Order $order)
    {
        return redirect()->route('orders.index');
    }

    function destroy(Order $order)
    {
        return redirect()->route('orders.index');
    }

}
