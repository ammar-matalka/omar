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
        // إضافة تتبع للتشخيص
        Log::info('تم استدعاء تحديث الطلب', ['order_id' => $order->id, 'request_data' => $request->all()]);
        
        // التحقق من صحة حقل الحالة
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ], [
            'status.required' => 'حالة الطلب مطلوبة.',
            'status.in' => 'حالة الطلب يجب أن تكون إحدى القيم التالية: قيد الانتظار، قيد المعالجة، تم الشحن، تم التسليم، ملغي.',
        ]);
        
        // تحديث حالة الطلب
        $order->status = $validated['status'];
        $order->save();
        
        // العودة مع رسالة نجاح
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
    
    public function destroy(Order $order)
    {
        try {
            // حذف عناصر الطلب المرتبطة أولاً للحفاظ على سلامة قاعدة البيانات
            $order->orderItems()->delete();
            
            // ثم حذف الطلب
            $order->delete();
            
            return redirect()->route('admin.orders.index')
                ->with('success', 'تم حذف الطلب رقم ' . $order->id . ' بنجاح.');
        } catch (\Exception $e) {
            Log::error('خطأ في حذف الطلب: ' . $e->getMessage());
            
            return redirect()->route('admin.orders.index')
                ->with('error', 'فشل في حذف الطلب. يرجى المحاولة مرة أخرى.');
        }
    }
}