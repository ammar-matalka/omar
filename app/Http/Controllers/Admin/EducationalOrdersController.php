<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalOrder;
use App\Models\EducationalGeneration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class EducationalOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'admin') {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = EducationalOrder::with(['user', 'generation', 'orderItems']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by order type
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        // Filter by generation
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        // Search by student name or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->recent()->paginate(20);
        $generations = EducationalGeneration::active()->ordered()->get();

        // Get statistics
        $stats = [
            'total_orders' => EducationalOrder::count(),
            'pending_orders' => EducationalOrder::pending()->count(),
            'processing_orders' => EducationalOrder::processing()->count(),
            'completed_orders' => EducationalOrder::completed()->count(),
            'cancelled_orders' => EducationalOrder::cancelled()->count(),
            'card_orders' => EducationalOrder::cards()->count(),
            'dossier_orders' => EducationalOrder::dossiers()->count(),
            'total_revenue' => EducationalOrder::completed()->sum('total_amount'),
            'today_orders' => EducationalOrder::whereDate('created_at', today())->count()
        ];

        return view('admin.educational-orders.index', compact('orders', 'generations', 'stats'));
    }

    public function show(EducationalOrder $order)
    {
        $order->load(['user', 'generation', 'orderItems']);
        
        return view('admin.educational-orders.show', compact('order'));
    }

    public function updateStatus(Request $request, EducationalOrder $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000'
        ], [
            'status.required' => 'حالة الطلب مطلوبة',
            'status.in' => 'حالة الطلب غير صحيحة',
            'admin_notes.max' => 'ملاحظات الأدمن لا يجب أن تتجاوز 1000 حرف'
        ]);

        $order->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        $statusText = $order->status_text;

        return back()->with('success', "تم تحديث حالة الطلب إلى: {$statusText}");
    }

    public function destroy(EducationalOrder $order)
    {
        // Only allow deletion of non-completed orders
        if ($order->status === 'completed') {
            return back()->withErrors(['error' => 'لا يمكن حذف الطلبات المكتملة']);
        }

        $order->delete();

        return redirect()->route('admin.educational-orders.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'exists:educational_orders,id',
            'action' => 'required|in:mark_processing,mark_completed,mark_cancelled,delete'
        ]);

        $orders = EducationalOrder::whereIn('id', $request->orders);

        switch ($request->action) {
            case 'mark_processing':
                $orders->update(['status' => 'processing']);
                $message = 'تم تحديث الطلبات المحددة إلى "قيد المعالجة"';
                break;
                
            case 'mark_completed':
                $orders->update(['status' => 'completed']);
                $message = 'تم تحديث الطلبات المحددة إلى "مكتملة"';
                break;
                
            case 'mark_cancelled':
                $orders->update(['status' => 'cancelled']);
                $message = 'تم تحديث الطلبات المحددة إلى "ملغاة"';
                break;
                
            case 'delete':
                // Only delete non-completed orders
                $orders->where('status', '!=', 'completed')->delete();
                $message = 'تم حذف الطلبات المحددة (عدا المكتملة)';
                break;
        }

        return back()->with('success', $message);
    }

    public function export(Request $request)
    {
        $query = EducationalOrder::with(['user', 'generation', 'orderItems']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }
        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->recent()->get();

        // Create CSV content
        $csvData = "ID,اسم المستخدم,ايميل المستخدم,اسم الطالب,الجيل,نوع الطلب,الفصل,الكمية,المجموع,الحالة,رقم الهاتف,العنوان,تاريخ الطلب,العناصر\n";

        foreach ($orders as $order) {
            $items = $order->orderItems->map(function($item) {
                return $item->item_name . ' (' . $item->item_type . ')';
            })->implode('; ');
            
            $csvData .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%d,%.2f,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $order->id,
                $order->user->name ?? '',
                $order->user->email ?? '',
                $order->student_name,
                $order->generation->display_name,
                $order->order_type_text,
                $order->semester_text,
                $order->quantity,
                $order->total_amount,
                $order->status_text,
                $order->phone ?? '',
                $order->address ?? '',
                $order->created_at->format('Y-m-d H:i:s'),
                $items
            );
        }

        $filename = 'educational_orders_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Encoding' => 'UTF-8',
            'BOM' => "\xEF\xBB\xBF"
        ]);
    }

    public function quickStats()
    {
        $stats = [
            'today_orders' => EducationalOrder::whereDate('created_at', today())->count(),
            'pending_orders' => EducationalOrder::pending()->count(),
            'total_revenue_this_month' => EducationalOrder::completed()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'card_orders_today' => EducationalOrder::cards()->whereDate('created_at', today())->count(),
            'dossier_orders_today' => EducationalOrder::dossiers()->whereDate('created_at', today())->count(),
        ];

        return response()->json($stats);
    }
}