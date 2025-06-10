<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
$orders = Order::with('user')->orderBy('id', 'asc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }
    
    public function update(Request $request, Order $order)
    {
        // Add debugging to trace the execution
        Log::info('Order update called', ['order_id' => $order->id, 'request_data' => $request->all()]);
        
        // Validate the status field
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        
        // Update the order status
        $order->status = $validated['status'];
        $order->save();
        
        // Redirect back with success message
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
    
    public function destroy(Order $order)
    {
        try {
            // Delete related order items first to maintain database integrity
            $order->orderItems()->delete();
            
            // Then delete the order
            $order->delete();
            
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order #' . $order->id . ' has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            
            return redirect()->route('admin.orders.index')
                ->with('error', 'Failed to delete order. Please try again.');
        }
    }
}