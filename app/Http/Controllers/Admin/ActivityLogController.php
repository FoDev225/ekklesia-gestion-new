<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->username, fn($q) => $q->where('username', 'like', "%{$request->username}%"))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        $users = User::orderBy('name')->get();

        $stats = [
            'total_logins'  => ActivityLog::where('type', 'login')->count(),
            'failed_today'  => ActivityLog::where('type', 'failed_login')->whereDate('created_at', today())->count(),
            'lockouts_today'=> ActivityLog::where('type', 'lockout')->whereDate('created_at', today())->count(),
        ];

        return view('admin.activity-logs.index', compact('logs', 'users', 'stats'));
    }
}