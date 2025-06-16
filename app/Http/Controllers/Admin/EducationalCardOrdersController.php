<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducationalCardOrder;
use App\Models\EducationalGeneration;
use Illuminate\Support\Facades\Response;

class EducationalCardOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = EducationalCardOrder::with(['user', 'generation', 'orderItems.subject']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
            'total_orders' => EducationalCardOrder::count(),
            'pending_orders' => EducationalCardOrder::pending()->count(),
            'processing_orders' => EducationalCardOrder::processing()->count(),
            'completed_orders' => EducationalCardOrder::completed()->count(),
            'total_revenue' => EducationalCardOrder::completed()->sum('total_amount'),
            'today_orders' => EducationalCardOrder::whereDate('created_at', today())->count()
        ];

        return view('admin.educational-card-orders.index', compact('orders', 'generations', 'stats'));
    }

    public function show(EducationalCardOrder $educationalCardOrder)
    {
        $educationalCardOrder->load(['user', 'generation', 'orderItems.subject']);
        
        return view('admin.educational-card-orders.show', compact('educationalCardOrder'));
    }

    public function updateStatus(Request $request, EducationalCardOrder $educationalCardOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000'
        ], [
            'status.required' => 'حالة الطلب مطلوبة',
            'status.in' => 'حالة الطلب غير صحيحة',
            'admin_notes.max' => 'ملاحظات الأدمن لا يجب أن تتجاوز 1000 حرف'
        ]);

        $educationalCardOrder->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        $statusText = $educationalCardOrder->status_text;

        return back()->with('success', "تم تحديث حالة الطلب إلى: {$statusText}");
    }

    public function destroy(EducationalCardOrder $educationalCardOrder)
    {
        // Only allow deletion of cancelled orders
        if ($educationalCardOrder->status === 'completed') {
            return back()->withErrors(['error' => 'لا يمكن حذف الطلبات المكتملة']);
        }

        $educationalCardOrder->delete();

        return redirect()->route('admin.educational-card-orders.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'exists:educational_card_orders,id',
            'action' => 'required|in:mark_processing,mark_completed,mark_cancelled,delete'
        ]);

        $orders = EducationalCardOrder::whereIn('id', $request->orders);

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
        $query = EducationalCardOrder::with(['user', 'generation', 'orderItems.subject']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        $csvData = "ID,اسم المستخدم,ايميل المستخدم,اسم الطالب,الجيل,الفصل,الكمية,المجموع,الحالة,رقم الهاتف,العنوان,تاريخ الطلب,المواد\n";

        foreach ($orders as $order) {
            $subjects = $order->orderItems->pluck('subject_name')->implode('; ');
            
            $csvData .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%d,%.2f,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $order->id,
                $order->user->name ?? '',
                $order->user->email ?? '',
                $order->student_name,
                $order->generation->display_name,
                $order->semester_text,
                $order->quantity,
                $order->total_amount,
                $order->status_text,
                $order->phone ?? '',
                $order->address ?? '',
                $order->created_at->format('Y-m-d H:i:s'),
                $subjects
            );
        }

        $filename = 'educational_card_orders_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Encoding' => 'UTF-8',
            'BOM' => "\xEF\xBB\xBF" // UTF-8 BOM for proper Arabic display in Excel
        ]);
    }

    public function quickStats()
    {
        $stats = [
            'today_orders' => EducationalCardOrder::whereDate('created_at', today())->count(),
            'pending_orders' => EducationalCardOrder::pending()->count(),
            'total_revenue_this_month' => EducationalCardOrder::completed()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount')
        ];

        return response()->json($stats);
    }
}