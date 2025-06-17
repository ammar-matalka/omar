<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducationalCardOrder;
use App\Models\EducationalGeneration;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class EducationalCardOrdersController extends Controller
{
    public function index(Request $request)
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }

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

        $orders = $query->latest()->paginate(20);
        $generations = EducationalGeneration::where('is_active', true)->orderBy('order')->orderBy('year', 'desc')->get();

        // Get statistics
        $stats = [
            'total_orders' => EducationalCardOrder::count(),
            'pending_orders' => EducationalCardOrder::where('status', 'pending')->count(),
            'processing_orders' => EducationalCardOrder::where('status', 'processing')->count(),
            'completed_orders' => EducationalCardOrder::where('status', 'completed')->count(),
            'total_revenue' => EducationalCardOrder::where('status', 'completed')->sum('total_amount'),
            'today_orders' => EducationalCardOrder::whereDate('created_at', today())->count()
        ];

        return view('admin.educational-card-orders.index', compact('orders', 'generations', 'stats'));
    }

    public function show(EducationalCardOrder $educationalCardOrder)
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $educationalCardOrder->load(['user', 'generation', 'orderItems.subject']);
        
        return view('admin.educational-card-orders.show', compact('educationalCardOrder'));
    }

    public function updateStatus(Request $request, EducationalCardOrder $educationalCardOrder)
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

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

        $statusText = $this->getStatusText($educationalCardOrder->status);

        return back()->with('success', "تم تحديث حالة الطلب إلى: {$statusText}");
    }

    public function destroy(EducationalCardOrder $educationalCardOrder)
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

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
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

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
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

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

        $orders = $query->latest()->get();

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
                $this->getSemesterText($order->semester),
                $order->quantity,
                $order->total_amount,
                $this->getStatusText($order->status),
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
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $stats = [
            'today_orders' => EducationalCardOrder::whereDate('created_at', today())->count(),
            'pending_orders' => EducationalCardOrder::where('status', 'pending')->count(),
            'total_revenue_this_month' => EducationalCardOrder::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount')
        ];

        return response()->json($stats);
    }

    private function getStatusText($status)
    {
        return match($status) {
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'completed' => 'مكتملة',
            'cancelled' => 'ملغاة',
            default => 'غير محدد'
        };
    }

    private function getSemesterText($semester)
    {
        return match($semester) {
            'first' => 'الفصل الأول',
            'second' => 'الفصل الثاني',
            'both' => 'كلا الفصلين',
            default => $semester
        };
    }
}