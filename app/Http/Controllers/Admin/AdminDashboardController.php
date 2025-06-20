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
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * عرض صفحة لوحة التحكم.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403, 'تم رفض الوصول. صلاحيات المدير مطلوبة.');
        }

        // الحصول على العدادات لبطاقات لوحة التحكم
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'customer')->count();
        
        // حساب إجمالي الإيرادات من الطلبات المسلمة
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');
        
        // الحصول على أحدث الطلبات لجدول الطلبات
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // الحصول على عدادات الشهادات حسب الحالة
        $pendingTestimonialsCount = Testimonial::where('status', 'pending')->count();
        $approvedTestimonialsCount = Testimonial::where('status', 'approved')->count();
        $rejectedTestimonialsCount = Testimonial::where('status', 'rejected')->count();
        
        // الحصول على أحدث الشهادات لجدول الشهادات
        $recentTestimonials = Testimonial::with(['user', 'order'])
            ->latest()
            ->take(5)
            ->get();
        
        // إضافة عدادات الرسائل والمحادثات للعدادات الجانبية
        $unreadConversationsCount = Conversation::where('is_read_by_admin', false)->count();
        $totalConversationsCount = Conversation::count();
        
        // الحصول على المحادثات الحديثة للعرض
        $recentConversations = Conversation::with(['user', 'lastMessage'])
            ->where('is_read_by_admin', false)
            ->latest('updated_at')
            ->take(5)
            ->get();
        
        // إحصائيات القسائم
        $activeCouponsCount = Coupon::where('is_used', false)
            ->where('valid_until', '>=', now())
            ->count();
        
        $usedCouponsCount = Coupon::where('is_used', true)
            ->count();
        
        $expiredCouponsCount = Coupon::where('is_used', false)
            ->where('valid_until', '<', now())
            ->count();
        
        $totalDiscounts = Order::sum('discount_amount');
        
        // === بيانات الرسوم البيانية ===
        
        // 1. بيانات مخطط المبيعات حسب الفئة (محسّن لـ SQLite)
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
            ->groupBy('categories.id', 'categories.name')
            ->get();
        
        // إعداد البيانات لـ Chart.js
        $categoryLabels = $salesByCategory->pluck('name');
        $categoryData = $salesByCategory->pluck('total_sales');
        
        // 2. بيانات مخطط الإيرادات الشهرية (محسّن لـ SQLite - آخر 6 أشهر)
        $monthlyRevenue = collect();
        
        // الحصول على البيانات باستخدام دوال التاريخ المتوافقة مع SQLite
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->startOfMonth()->format('Y-m-d H:i:s');
            $endOfMonth = $date->endOfMonth()->format('Y-m-d H:i:s');
            
            $revenue = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('total_amount');
            
            $monthlyRevenue->push([
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ]);
        }
        
        // إعداد البيانات الشهرية لـ Chart.js
        $monthlyLabels = $monthlyRevenue->pluck('month');
        $monthlyData = $monthlyRevenue->pluck('revenue');
        
        // إرجاع العرض مع جميع المتغيرات بما في ذلك عدادات الإشعارات
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
     * الحصول على عدادات الإشعارات لطلبات AJAX
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationCounts()
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $unreadConversationsCount = Conversation::where('is_read_by_admin', false)->count();
        $pendingTestimonialsCount = Testimonial::where('status', 'pending')->count();
        
        return response()->json([
            'unread_conversations' => $unreadConversationsCount,
            'pending_testimonials' => $pendingTestimonialsCount,
        ]);
    }
    
    /**
     * تحديد جميع المحادثات كمقروءة
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllConversationsRead()
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        Conversation::where('is_read_by_admin', false)
            ->update(['is_read_by_admin' => true]);
        
        return redirect()->back()->with('success', 'تم تحديد جميع المحادثات كمقروءة.');
    }
    
    /**
     * تحديد جميع الشهادات كمراجعة
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllTestimonialsReviewed()
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // هذا فقط لمسح شارة الإشعار
        // قد تريد إضافة حالة "مراجع" أو التعامل بشكل مختلف
        return redirect()->back()->with('success', 'تم مسح جميع إشعارات الشهادات.');
    }
    
    /**
     * الحصول على إحصائيات لوحة التحكم للعناصر المصغرة
     *
     * @return array
     */
    private function getDashboardStats()
    {
        return [
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
                'out_of_stock' => Product::where('stock', '<=', 0)->count(),
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
                'low_stock_products' => Product::where('stock', '<=', 5)->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
            ]
        ];
    }
    
    /**
     * تصدير بيانات لوحة التحكم إلى CSV
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportDashboardData()
    {
        // تحقق من الأدمن
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $stats = $this->getDashboardStats();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dashboard_stats_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            
            // إضافة عناوين CSV
            fputcsv($file, ['المقياس', 'القيمة', 'الفئة']);
            
            // إضافة صفوف البيانات
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