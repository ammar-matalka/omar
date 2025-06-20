<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $orders = $user->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'وصول غير مخول لهذا الطلب.');
        }
        
        $order->load('orderItems.product');
        
        return view('orders.show', compact('order'));
    }
}