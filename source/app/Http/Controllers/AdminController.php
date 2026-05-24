<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vendor;
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
}
