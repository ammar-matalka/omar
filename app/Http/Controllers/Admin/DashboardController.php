<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Testimonial;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get counts for dashboard cards
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'customer')->count();
        
        // Calculate total revenue from delivered orders
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');
        
        // Get recent orders for the orders table
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Get testimonial counts by status
        $pendingTestimonialsCount = Testimonial::where('status', 'pending')->count();
        $approvedTestimonialsCount = Testimonial::where('status', 'approved')->count();
        $rejectedTestimonialsCount = Testimonial::where('status', 'rejected')->count();
        
        // Get recent testimonials for the testimonials table
        $recentTestimonials = Testimonial::with(['user', 'order'])
            ->latest()
            ->take(5)
            ->get();
        
        // إضافة عدادات الرسائل والمحادثات للعدادات الجانبية
        $unreadConversationsCount = Conversation::where('is_read_by_admin', false)->count();
        $totalConversationsCount = Conversation::count();
        
        // Get recent conversations for display
        $recentConversations = Conversation::with(['user', 'lastMessage'])
            ->where('is_read_by_admin', false)
            ->latest('updated_at')
            ->take(5)
            ->get();
        
        // Coupon statistics
        $activeCouponsCount = Coupon::where('is_used', false)
            ->where('valid_until', '>=', now())
            ->count();
        
        $usedCouponsCount = Coupon::where('is_used', true)
            ->count();
        
        $expiredCouponsCount = Coupon::where('is_used', false)
            ->where('valid_until', '<', now())
            ->count();
        
        $totalDiscounts = Order::sum('discount_amount');
        
        // === DATA FOR CHARTS ===
        
        // 1. Sales by Category Chart Data
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
            ->groupBy('categories.id', 'categories.name')
            ->get();
        
        // Prepare data for Chart.js
        $categoryLabels = $salesByCategory->pluck('name');
        $categoryData = $salesByCategory->pluck('total_sales');
        
        // 2. Monthly Revenue Chart Data (last 6 months)
        $monthlyRevenue = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Prepare monthly data for Chart.js
        $monthlyLabels = [];
        $monthlyData = [];
        
        // Fill in missing months with 0
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $monthlyLabels[] = $monthName;
            
            $monthData = $monthlyRevenue->where('year', $date->year)
                                      ->where('month', $date->month)
                                      ->first();
            
            $monthlyData[] = $monthData ? (float)$monthData->total : 0;
        }
        
        // Return the view with all variables including notification counts
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'pendingTestimonialsCount',
            'approvedTestimonialsCount',
            'rejectedTestimonialsCount',
            'recentTestimonials',
            'unreadConversationsCount',    // عداد الرسائل غير المقروءة
            'totalConversationsCount',     // إجمالي المحادثات
            'recentConversations',         // المحادثات الحديثة
            'activeCouponsCount',
            'usedCouponsCount',
            'expiredCouponsCount',
            'totalDiscounts',
            'categoryLabels',
            'categoryData',
            'monthlyLabels',
            'monthlyData'
        ));
    }
    
    /**
     * Get notification counts for AJAX requests
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationCounts()
    {
        $unreadConversationsCount = Conversation::where('is_read_by_admin', false)->count();
        $pendingTestimonialsCount = Testimonial::where('status', 'pending')->count();
        
        return response()->json([
            'unread_conversations' => $unreadConversationsCount,
            'pending_testimonials' => $pendingTestimonialsCount,
        ]);
    }
    
    /**
     * Mark all conversations as read
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllConversationsRead()
    {
        Conversation::where('is_read_by_admin', false)
            ->update(['is_read_by_admin' => true]);
        
        return redirect()->back()->with('success', 'All conversations marked as read.');
    }
    
    /**
     * Mark all testimonials as reviewed
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllTestimonialsReviewed()
    {
        // This is just for clearing the notification badge
        // You might want to add a 'reviewed' status or handle differently
        return redirect()->back()->with('success', 'All testimonials notifications cleared.');
    }
    
    /**
     * Get dashboard statistics for widgets
     *
     * @return array
     */
    private function getDashboardStats()
    {
        return [
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('status', 'active')->count(),
                'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            ],
            'orders' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ],
            'users' => [
                'total_customers' => User::where('role', 'customer')->count(),
                'total_admins' => User::where('role', 'admin')->count(),
                'new_this_month' => User::where('role', 'customer')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ],
            'revenue' => [
                'total' => Order::where('status', 'delivered')->sum('total_amount'),
                'this_month' => Order::where('status', 'delivered')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total_amount'),
                'last_month' => Order::where('status', 'delivered')
                    ->whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year)
                    ->sum('total_amount'),
            ],
            'notifications' => [
                'unread_conversations' => Conversation::where('is_read_by_admin', false)->count(),
                'pending_testimonials' => Testimonial::where('status', 'pending')->count(),
                'low_stock_products' => Product::where('stock_quantity', '<=', 5)->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
            ]
        ];
    }
    
    /**
     * Export dashboard data to CSV
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportDashboardData()
    {
        $stats = $this->getDashboardStats();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dashboard_stats_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Metric', 'Value', 'Category']);
            
            // Add data rows
            foreach ($stats as $category => $data) {
                foreach ($data as $metric => $value) {
                    fputcsv($file, [ucfirst(str_replace('_', ' ', $metric)), $value, ucfirst($category)]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}