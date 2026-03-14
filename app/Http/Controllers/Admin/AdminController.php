<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Streamer;
use App\Models\MonitorLog;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $stats = [
            'users' => User::count(),
            'streamers' => Streamer::count(),
            'monitor_logs' => MonitorLog::count(),
            'active_streamers' => Streamer::where('is_monitoring', true)->count(),
        ];

        $recentUsers = User::latest()->limit(5)->get();
        $recentStreamers = Streamer::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentStreamers'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? '启用' : '禁用';
        return back()->with('success', "用户已{$status}");
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', '用户删除成功');
    }

    public function streamers()
    {
        $streamers = Streamer::with('user')->latest()->paginate(20);
        return view('admin.streamers', compact('streamers'));
    }

    public function deleteStreamer(Streamer $streamer)
    {
        $streamer->delete();
        return back()->with('success', '主播删除成功');
    }

    public function logs()
    {
        $logs = MonitorLog::with(['user', 'streamer'])->latest()->paginate(50);
        return view('admin.logs', compact('logs'));
    }
}