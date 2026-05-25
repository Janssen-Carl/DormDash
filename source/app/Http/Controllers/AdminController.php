<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /* ──────────────────────────────────────
     * Dashboard
     * ────────────────────────────────────── */
    public function index()
    {
        $totalUsers     = User::count();
        $totalVendors   = User::where('role', 'vendor')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Vendors pending approval (active = 0)
        $pendingVendors = Vendor::where('active', false)->count();

        // Active vendors
        $activeVendors  = Vendor::where('active', true)->count();

        // Recent registrations (last 7 days)
        $newUsersThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();

        // Recent admin activity (latest 8 logs)
        $recentLogs = AdminLog::with('admin')
            ->latest()
            ->take(8)
            ->get();

        // New registrations per day (last 14 days) for chart
        $registrationChart = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData   = [];
        for ($i = 13; $i >= 0; $i--) {
            $day           = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartData[]   = $registrationChart[$day]->count ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVendors',
            'totalCustomers',
            'pendingVendors',
            'activeVendors',
            'newUsersThisWeek',
            'recentLogs',
            'chartLabels',
            'chartData'
        ));
    }

    /* ──────────────────────────────────────
     * Account Management
     * ────────────────────────────────────── */
    public function accounts(Request $request)
    {
        $roleFilter   = $request->query('role', 'all');
        $statusFilter = $request->query('status', 'all');
        $search       = $request->query('search', '');
        $sortBy       = in_array($request->query('sort'), ['username', 'email', 'role', 'created_at'])
                            ? $request->query('sort') : 'created_at';
        $sortDir      = $request->query('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = User::with(['vendor', 'customer'])
            ->whereIn('role', ['vendor', 'customer']);

        if ($roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($statusFilter === 'active') {
            $query->where(function ($q) {
                $q->whereHas('vendor', fn($v) => $v->where('active', true))
                  ->orWhere('role', 'customer');
            });
        } elseif ($statusFilter === 'pending') {
            $query->whereHas('vendor', fn($v) => $v->where('active', false));
        }

        $users = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        return view('admin.accounts', compact('users', 'roleFilter', 'statusFilter', 'search', 'sortBy', 'sortDir'));
    }

    /* ──────────────────────────────────────
     * Approve Vendor
     * ────────────────────────────────────── */
    public function approveVendor($id)
    {
        $user   = User::with('vendor')->findOrFail($id);
        $vendor = $user->vendor;

        if (!$vendor) {
            return back()->withErrors(['error' => 'No vendor profile found for this user.']);
        }

        $vendor->active = true;
        $vendor->save();

        AdminLog::create([
            'admin_id'        => Auth::id(),
            'action'          => 'approved_vendor',
            'target_user_id'  => $user->user_id,
            'target_username' => $user->username,
            'target_role'     => 'vendor',
            'notes'           => "Vendor '{$vendor->name}' approved and set to active.",
        ]);

        return back()->with('success', "Vendor '{$user->username}' has been approved.");
    }

    /* ──────────────────────────────────────
     * Delete User
     * ────────────────────────────────────── */
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->user_id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own admin account.']);
        }

        $role     = $user->role;
        $username = $user->username;

        DB::transaction(function () use ($user, $role, $username) {
            // Log before deletion so we have admin context
            AdminLog::create([
                'admin_id'        => Auth::id(),
                'action'          => "deleted_{$role}",
                'target_user_id'  => $user->user_id,
                'target_username' => $username,
                'target_role'     => $role,
                'notes'           => "Account permanently deleted by admin.",
            ]);

            // Cascade delete handled by DB constraints
            $user->delete();
        });

        return back()->with('success', "Account '{$username}' has been permanently deleted.");
    }

    /* ──────────────────────────────────────
     * Activity Logs
     * ────────────────────────────────────── */
    public function logs(Request $request)
    {
        $search       = $request->query('search', '');
        $actionFilter = $request->query('action', 'all');
        $sortBy       = in_array($request->query('sort'), ['created_at', 'action', 'target_username'])
                            ? $request->query('sort') : 'created_at';
        $sortDir      = $request->query('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = AdminLog::with('admin');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('target_username', 'like', "%{$search}%")
                  ->orWhereHas('admin', fn($a) => $a->where('username', 'like', "%{$search}%"));
            });
        }

        if ($actionFilter !== 'all') {
            $query->where('action', $actionFilter);
        }

        $logs = $query->orderBy($sortBy, $sortDir)->paginate(20)->withQueryString();

        $actionTypes = AdminLog::select('action')->distinct()->pluck('action');

        return view('admin.logs', compact('logs', 'search', 'actionFilter', 'actionTypes', 'sortBy', 'sortDir'));
    }

    /* ──────────────────────────────────────
     * Export Logs as CSV
     * ────────────────────────────────────── */
    public function exportLogs(Request $request)
    {
        $search       = $request->query('search', '');
        $actionFilter = $request->query('action', 'all');

        $query = AdminLog::with('admin')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('target_username', 'like', "%{$search}%")
                  ->orWhereHas('admin', fn($a) => $a->where('username', 'like', "%{$search}%"));
            });
        }

        if ($actionFilter !== 'all') {
            $query->where('action', $actionFilter);
        }

        $logs = $query->get();

        $filename = 'admin_logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, ['ID', 'Timestamp', 'Action', 'Target Username', 'Target Role', 'Performed By', 'Notes']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->action_label,
                    $log->target_username,
                    $log->target_role ?? '',
                    $log->admin->username ?? 'Admin #' . $log->admin_id,
                    $log->notes ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /* ──────────────────────────────────────
     * Platform-wide Sales & Product Insights
     * ────────────────────────────────────── */
    public function insights(Request $request)
    {
        $timeframe = $request->query('timeframe', 'all'); // '7', '30', 'all'
        
        // Determine date filter for active query
        $days = ($timeframe === 'all') ? 30 : intval($timeframe);
        $currentStart = now()->subDays($days);
        $previousStart = now()->subDays($days * 2);
        
        $query = Order::query();
        
        if ($timeframe !== 'all') {
            $query->where('created_at', '>=', $currentStart);
        }

        // 1. Core Summary Metrics
        $ordersCount = (clone $query)->count();
        $totalRevenue = (clone $query)->where('order_status', '!=', 'cancelled')->sum('order_total');
        
        $totalItemsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->when($timeframe !== 'all', function($q) use ($currentStart) {
                return $q->where('orders.created_at', '>=', $currentStart);
            })
            ->sum('quantity');

        $averageOrderValue = $ordersCount > 0 ? ($totalRevenue / $ordersCount) : 0;
        $totalActiveProducts = Item::where('is_active', true)->count();

        // 2. Core Metrics Growth (Period over Period)
        // Current Revenue
        $currRev = Order::where('order_status', '!=', 'cancelled')
            ->where('created_at', '>=', $currentStart)
            ->sum('order_total');
        // Previous Revenue
        $prevRev = Order::where('order_status', '!=', 'cancelled')
            ->where('created_at', '>=', $previousStart)
            ->where('created_at', '<', $currentStart)
            ->sum('order_total');
        $revenueGrowth = $prevRev > 0 ? (($currRev - $prevRev) / $prevRev) * 100 : ($currRev > 0 ? 100 : 0);

        // Current Orders
        $currOrd = Order::where('created_at', '>=', $currentStart)->count();
        // Previous Orders
        $prevOrd = Order::where('created_at', '>=', $previousStart)->where('created_at', '<', $currentStart)->count();
        $ordersGrowth = $prevOrd > 0 ? (($currOrd - $prevOrd) / $prevOrd) * 100 : ($currOrd > 0 ? 100 : 0);

        // Current Items Sold
        $currItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->where('orders.created_at', '>=', $currentStart)
            ->sum('quantity');
        // Previous Items Sold
        $prevItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->where('orders.created_at', '>=', $previousStart)
            ->where('orders.created_at', '<', $currentStart)
            ->sum('quantity');
        $itemsGrowth = $prevItems > 0 ? (($currItems - $prevItems) / $prevItems) * 100 : ($currItems > 0 ? 100 : 0);

        // Average Order Value growth
        $currAOV = $currOrd > 0 ? ($currRev / $currOrd) : 0;
        $prevAOV = $prevOrd > 0 ? ($prevRev / $prevOrd) : 0;
        $aovGrowth = $prevAOV > 0 ? (($currAOV - $prevAOV) / $prevAOV) * 100 : ($currAOV > 0 ? 100 : 0);

        // 3. Orders by Status
        $ordersByStatus = (clone $query)
            ->select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status')
            ->toArray();

        // Ensure all possible statuses are present in the list
        $allStatuses = ['pending', 'to_ship', 'shipped', 'delivered', 'completed', 'cancelled'];
        foreach ($allStatuses as $status) {
            if (!isset($ordersByStatus[$status])) {
                $ordersByStatus[$status] = 0;
            }
        }

        // 4. Daily Revenue & Orders Trend
        if ($timeframe === '7') {
            $daysCount = 7;
        } elseif ($timeframe === '30') {
            $daysCount = 30;
        } else {
            $daysCount = 60; // Show last 60 days for "All Time" to make graph look rich
        }

        $dailyStats = (clone $query)
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(CASE WHEN order_status != "cancelled" THEN order_total ELSE 0 END) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartRevenueData = [];
        $chartOrderData = [];

        for ($i = $daysCount - 1; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartRevenueData[] = floatval($dailyStats[$dateStr]->revenue ?? 0);
            $chartOrderData[] = intval($dailyStats[$dateStr]->order_count ?? 0);
        }

        // 5. Top Selling Products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->join('items', 'order_items.item_id', '=', 'items.item_id')
            ->leftJoin('vendors', 'items.vendor_id', '=', 'vendors.vendor_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->when($timeframe !== 'all', function($q) use ($currentStart) {
                return $q->where('orders.created_at', '>=', $currentStart);
            })
            ->select(
                'items.item_id',
                'items.name',
                'items.price',
                'items.stock',
                'items.brand',
                'vendors.name as vendor_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('items.item_id', 'items.name', 'items.price', 'items.stock', 'items.brand', 'vendors.name')
            ->orderBy('total_sold', 'desc')
            ->take(8)
            ->get();

        // 6. Vendor Performance Leaderboard
        $vendorPerformance = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->join('items', 'order_items.item_id', '=', 'items.item_id')
            ->join('vendors', 'items.vendor_id', '=', 'vendors.vendor_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->when($timeframe !== 'all', function($q) use ($currentStart) {
                return $q->where('orders.created_at', '>=', $currentStart);
            })
            ->select(
                'vendors.vendor_id',
                'vendors.name',
                'vendors.active',
                DB::raw('COUNT(DISTINCT orders.order_id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_items_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('vendors.vendor_id', 'vendors.name', 'vendors.active')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // 7. Recent Orders List
        $recentOrders = Order::with(['customer.user', 'paymentTransaction', 'items.vendor'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // 8. Global AI Forecasting microservice call
        $aiBase = env('AI_API_URL');
        $candidates = array_filter([
            $aiBase,
            env('AI_API_URL_ALT', null),
            'http://ai:5000',
            'http://host.docker.internal:5000',
            'http://127.0.0.1:5000',
        ]);

        $aiForecast = null;
        $aiSummary = null;

        foreach ($candidates as $base) {
            try {
                \Illuminate\Support\Facades\Log::info("Admin Insights: trying AI base URL: {$base}");
                
                $forecastResp = \Illuminate\Support\Facades\Http::timeout(3)
                    ->get($base . '/forecast/global/revenue', [
                        'days' => 90,
                        'horizon' => 7,
                    ]);

                if ($forecastResp->successful()) {
                    $aiForecast = $forecastResp->json();
                    
                    $summaryResp = \Illuminate\Support\Facades\Http::timeout(3)
                        ->get($base . '/forecast/global/summary', [
                            'days' => 90,
                            'horizon' => 1,
                        ]);
                        
                    $aiSummary = $summaryResp->successful() ? $summaryResp->json() : null;
                    break;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Admin Insights: error calling AI at {$base}: " . $e->getMessage());
            }
        }

        $aiLabels = [];
        $aiRevenue = [];
        $predictedNextDay = 0.0;
        $predictedGrowth = 0.0;
        $aiOnline = false;

        if ($aiForecast) {
            $aiOnline = true;
            if (!empty($aiForecast['dates'])) {
                foreach ($aiForecast['dates'] as $date) {
                    $aiLabels[] = \Carbon\Carbon::parse($date)->format('M. d');
                }
            }
            if (!empty($aiForecast['predicted'])) {
                $aiRevenue = $aiForecast['predicted'];
            }
            if ($aiSummary) {
                $predictedNextDay = $aiSummary['predicted_next_day'] ?? 0.0;
                $predictedGrowth = $aiSummary['percent_change'] ?? 0.0;
            }
        }

        return view('admin.insights', compact(
            'timeframe',
            'ordersCount',
            'totalRevenue',
            'totalItemsSold',
            'averageOrderValue',
            'totalActiveProducts',
            'ordersByStatus',
            'chartLabels',
            'chartRevenueData',
            'chartOrderData',
            'topProducts',
            'vendorPerformance',
            'recentOrders',
            'revenueGrowth',
            'ordersGrowth',
            'itemsGrowth',
            'aovGrowth',
            'aiLabels',
            'aiRevenue',
            'predictedNextDay',
            'predictedGrowth',
            'aiOnline'
        ));
    }

    /* ──────────────────────────────────────
     * Export Insights as Structured CSV
     * ────────────────────────────────────── */
    public function exportInsights(Request $request)
    {
        $timeframe = $request->query('timeframe', 'all'); // '7', '30', 'all'
        
        $days = ($timeframe === 'all') ? 30 : intval($timeframe);
        $currentStart = now()->subDays($days);
        
        $query = Order::query();
        if ($timeframe !== 'all') {
            $query->where('created_at', '>=', $currentStart);
        }

        // Metrics
        $ordersCount = (clone $query)->count();
        $totalRevenue = (clone $query)->where('order_status', '!=', 'cancelled')->sum('order_total');
        $totalItemsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->when($timeframe !== 'all', function($q) use ($currentStart) {
                return $q->where('orders.created_at', '>=', $currentStart);
            })
            ->sum('quantity');
        $averageOrderValue = $ordersCount > 0 ? ($totalRevenue / $ordersCount) : 0;
        $totalActiveProducts = Item::where('is_active', true)->count();

        // Vendor Performance Leaderboard
        $vendorPerformance = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->join('items', 'order_items.item_id', '=', 'items.item_id')
            ->join('vendors', 'items.vendor_id', '=', 'vendors.vendor_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->when($timeframe !== 'all', function($q) use ($currentStart) {
                return $q->where('orders.created_at', '>=', $currentStart);
            })
            ->select(
                'vendors.name',
                'vendors.active',
                DB::raw('COUNT(DISTINCT orders.order_id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_items_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('vendors.vendor_id', 'vendors.name', 'vendors.active')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Top Products Leaderboard
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->join('items', 'order_items.item_id', '=', 'items.item_id')
            ->leftJoin('vendors', 'items.vendor_id', '=', 'vendors.vendor_id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->when($timeframe !== 'all', function($q) use ($currentStart) {
                return $q->where('orders.created_at', '>=', $currentStart);
            })
            ->select(
                'items.name',
                'items.brand',
                'vendors.name as vendor_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                'items.stock',
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('items.item_id', 'items.name', 'items.price', 'items.stock', 'items.brand', 'vendors.name')
            ->orderBy('total_sold', 'desc')
            ->get();

        // Daily trend
        $dailyStats = (clone $query)
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(CASE WHEN order_status != "cancelled" THEN order_total ELSE 0 END) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'platform_insights_' . $timeframe . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($timeframe, $ordersCount, $totalRevenue, $averageOrderValue, $totalItemsSold, $totalActiveProducts, $vendorPerformance, $topProducts, $dailyStats) {
            $handle = fopen('php://output', 'w');

            // 1. PERFORMANCE SUMMARY
            fputcsv($handle, ['--- PLATFORM PERFORMANCE SUMMARY (Timeframe: ' . strtoupper($timeframe) . ') ---']);
            fputcsv($handle, ['Metric', 'Value']);
            fputcsv($handle, ['Total Sales Revenue', 'PHP ' . number_format($totalRevenue, 2)]);
            fputcsv($handle, ['Total Order Volume', $ordersCount]);
            fputcsv($handle, ['Average Order Value', 'PHP ' . number_format($averageOrderValue, 2)]);
            fputcsv($handle, ['Total Products Sold', $totalItemsSold]);
            fputcsv($handle, ['Total Active Products', $totalActiveProducts]);
            fputcsv($handle, []);

            // 2. VENDOR LEADERBOARD
            fputcsv($handle, ['--- VENDOR SALES PERFORMANCE LEADERBOARD ---']);
            fputcsv($handle, ['Rank', 'Vendor Name', 'Total Orders', 'Units Sold', 'Total Revenue', 'Status']);
            foreach ($vendorPerformance as $index => $vendor) {
                fputcsv($handle, [
                    $index + 1,
                    $vendor->name,
                    $vendor->total_orders,
                    $vendor->total_items_sold,
                    'PHP ' . number_format($vendor->total_revenue, 2),
                    $vendor->active ? 'Active' : 'Inactive'
                ]);
            }
            fputcsv($handle, []);

            // 3. PRODUCT LEADERBOARD
            fputcsv($handle, ['--- PRODUCT SALES LEADERBOARD ---']);
            fputcsv($handle, ['Rank', 'Product Name', 'Brand', 'Vendor', 'Units Sold', 'Stock Left', 'Total Revenue']);
            foreach ($topProducts as $index => $product) {
                fputcsv($handle, [
                    $index + 1,
                    $product->name,
                    $product->brand ?? 'No Brand',
                    $product->vendor_name ?? 'N/A',
                    $product->total_sold,
                    $product->stock,
                    'PHP ' . number_format($product->total_revenue, 2)
                ]);
            }
            fputcsv($handle, []);

            // 4. DAILY SALES TREND
            fputcsv($handle, ['--- DAILY SALES TREND ---']);
            fputcsv($handle, ['Date', 'Revenue', 'Orders Count']);
            foreach ($dailyStats as $row) {
                fputcsv($handle, [
                    $row->date,
                    'PHP ' . number_format($row->revenue, 2),
                    $row->order_count
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
