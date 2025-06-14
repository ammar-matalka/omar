<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalCardOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EducationalCardOrderController extends Controller
{
    /**
     * Display a listing of educational card orders
     */
    public function index(Request $request)
    {
        $query = EducationalCardOrder::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject', 'LIKE', '%' . $request->subject . '%');
        }

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $orders = $query->paginate(15)->appends($request->query());

        // Get summary statistics
        $stats = [
            'total_orders' => EducationalCardOrder::count(),
            'pending_orders' => EducationalCardOrder::where('status', 'pending')->count(),
            'processing_orders' => EducationalCardOrder::where('status', 'processing')->count(),
            'completed_orders' => EducationalCardOrder::where('status', 'completed')->count(),
            'total_revenue' => EducationalCardOrder::where('status', 'completed')->sum('total_amount'),
        ];

        // Get unique values for filters
        $academicYears = EducationalCardOrder::distinct()->pluck('academic_year')->sort()->values();
        $subjects = EducationalCardOrder::distinct()->pluck('subject')->sort()->values();

        return view('admin.educational-card-orders.index', compact(
            'orders', 'stats', 'academicYears', 'subjects'
        ));
    }

    /**
     * Display the specified order
     */
    public function show(EducationalCardOrder $educationalCardOrder)
    {
        $educationalCardOrder->load('user');
        return view('admin.educational-card-orders.show', compact('educationalCardOrder'));
    }

    /**
     * Update the order status
     */
    public function updateStatus(Request $request, EducationalCardOrder $educationalCardOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $educationalCardOrder->update([
                'status' => $validated['status'],
                'is_processed' => in_array($validated['status'], ['completed', 'cancelled'])
            ]);

            // Add admin notes if provided
            if ($validated['admin_notes']) {
                $educationalCardOrder->update([
                    'notes' => $educationalCardOrder->notes . "\n\n--- Admin Notes ---\n" . $validated['admin_notes']
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Order status updated successfully'),
                    'status' => $educationalCardOrder->status
                ]);
            }

            return redirect()->route('admin.educational-card-orders.show', $educationalCardOrder)
                ->with('success', __('Order status updated successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Error updating order status')
                ], 500);
            }

            return back()->with('error', __('Error updating order status'));
        }
    }

    /**
     * Delete an order
     */
    public function destroy(EducationalCardOrder $educationalCardOrder)
    {
        try {
            $educationalCardOrder->delete();
            
            return redirect()->route('admin.educational-card-orders.index')
                ->with('success', __('Order deleted successfully'));
                
        } catch (\Exception $e) {
            return back()->with('error', __('Error deleting order'));
        }
    }

    /**
     * Bulk update orders
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:educational_card_orders,id',
            'action' => 'required|in:mark_processing,mark_completed,mark_cancelled,delete',
        ]);

        try {
            DB::beginTransaction();

            $orderIds = $validated['order_ids'];

            switch ($validated['action']) {
                case 'mark_processing':
                    EducationalCardOrder::whereIn('id', $orderIds)
                        ->update(['status' => 'processing']);
                    $message = __('Orders marked as processing');
                    break;

                case 'mark_completed':
                    EducationalCardOrder::whereIn('id', $orderIds)
                        ->update(['status' => 'completed', 'is_processed' => true]);
                    $message = __('Orders marked as completed');
                    break;

                case 'mark_cancelled':
                    EducationalCardOrder::whereIn('id', $orderIds)
                        ->update(['status' => 'cancelled', 'is_processed' => true]);
                    $message = __('Orders marked as cancelled');
                    break;

                case 'delete':
                    EducationalCardOrder::whereIn('id', $orderIds)->delete();
                    $message = __('Orders deleted successfully');
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('Error processing bulk action')
            ], 500);
        }
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = EducationalCardOrder::with('user');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $filename = 'educational_card_orders_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Order ID',
                'User Name',
                'User Email',
                'Academic Year',
                'Generation',
                'Subject',
                'Teacher',
                'Semester',
                'Platform',
                'Notebook Type',
                'Quantity',
                'Total Amount',
                'Status',
                'Created At',
                'Notes'
            ]);
            
            // CSV data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name,
                    $order->user->email,
                    $order->academic_year,
                    $order->generation,
                    $order->subject,
                    $order->teacher,
                    $order->semester,
                    $order->platform,
                    $order->notebook_type,
                    $order->quantity,
                    $order->total_amount,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->notes
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}