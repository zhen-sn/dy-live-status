<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Streamer;
use App\Models\MonitorLog;
use App\Services\DouyinService;

class DashboardController extends Controller
{
    protected $douyinService;

    public function __construct(DouyinService $douyinService)
    {
        $this->douyinService = $douyinService;
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $streamers = $user->streamers()->latest()->get();
        $recentLogs = $user->monitorLogs()->latest()->limit(10)->get();

        return view('dashboard', compact('streamers', 'recentLogs'));
    }

    public function addStreamer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'douyin_url' => 'required|url',
        ]);

        $douyinId = $this->douyinService->parseDouyinUrl($request->douyin_url);

        if (!$douyinId) {
            return back()->withErrors(['douyin_url' => '无法解析抖音URL，请检查链接是否正确']);
        }

        $user = auth()->user();

        $streamer = Streamer::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'douyin_url' => $request->douyin_url,
            'douyin_id' => $douyinId,
        ]);

        return back()->with('success', '主播添加成功！');
    }

    public function deleteStreamer(Streamer $streamer)
    {
        if ($streamer->user_id !== auth()->id()) {
            return back()->withErrors(['error' => '无权删除此主播']);
        }

        $streamer->delete();
        return back()->with('success', '主播删除成功！');
    }

    public function toggleMonitoring(Streamer $streamer)
    {
        if ($streamer->user_id !== auth()->id()) {
            return back()->withErrors(['error' => '无权操作此主播']);
        }

        $streamer->update([
            'is_monitoring' => !$streamer->is_monitoring,
        ]);

        $status = $streamer->is_monitoring ? '开启' : '关闭';
        return back()->with('success', "监控已{$status}");
    }

    public function checkNow(Streamer $streamer)
    {
        if ($streamer->user_id !== auth()->id()) {
            return back()->withErrors(['error' => '无权操作此主播']);
        }

        $result = $this->douyinService->checkLiveStatus($streamer);

        $streamer->update([
            'is_live' => $result['is_live'],
            'last_check_time' => now(),
        ]);

        MonitorLog::create([
            'user_id' => $streamer->user_id,
            'streamer_id' => $streamer->id,
            'was_live' => false,
            'is_live' => $result['is_live'],
            'notification_sent' => false,
            'response_data' => json_encode($result),
        ]);

        return back()->with('success', "检测完成：{$result['message']}");
    }

    public function settings()
    {
        $user = auth()->user();
        return view('settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', '设置更新成功！');
    }
}