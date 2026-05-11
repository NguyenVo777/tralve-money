<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Scan;
use App\Models\ConversionHistory;
use App\Models\ExchangeRate;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_scans' => Scan::count(),
            'total_conversions' => ConversionHistory::count(),
            'popular_country' => User::select('country', DB::raw('count(*) as total'))
                ->groupBy('country')
                ->orderBy('total', 'desc')
                ->first()?->country ?? 'N/A',
            'popular_currency' => ConversionHistory::select('to_currency', DB::raw('count(*) as total'))
                ->groupBy('to_currency')
                ->orderBy('total', 'desc')
                ->first()?->to_currency ?? 'N/A',
        ];

        // Realtime stats (last 24h)
        $realtime = [
            'new_users' => User::where('created_at', '>=', now()->subDay())->count(),
            'new_scans' => Scan::where('created_at', '>=', now()->subDay())->count(),
            'new_scans_list' => Scan::with('user')->orderBy('created_at', 'desc')->take(5)->get(),
        ];

        // Chart data
        $rateCharts = ExchangeRate::with(['history' => function($query) {
            $query->orderBy('created_at', 'asc')->take(10);
        }])->get();

        $accuracyChart = Scan::select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(accuracy) as avg_accuracy'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->take(7)
            ->get();

        return view('admin.dashboard', compact('stats', 'realtime', 'rateCharts', 'accuracyChart'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function userDetail($id)
    {
        $user = User::with(['scans', 'conversions', 'activityLogs', 'loginLogs'])->findOrFail($id);
        return view('admin.user-detail', compact('user'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'active' ? 'locked' : 'active';
        $user->save();

        return back()->with('success', 'User status updated.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }
}
