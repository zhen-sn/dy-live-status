<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DouyinService;
use App\Services\SmsService;
use App\Models\Streamer;
use App\Models\MonitorLog;
use Illuminate\Support\Facades\Log;

class MonitorLiveStatus extends Command
{
    protected $signature = 'monitor:live';
    protected $description = '监控抖音主播直播状态';

    protected $douyinService;
    protected $smsService;

    public function __construct(DouyinService $douyinService, SmsService $smsService)
    {
        parent::__construct();
        $this->douyinService = $douyinService;
        $this->smsService = $smsService;
    }

    public function handle()
    {
        $this->info('开始监控直播状态...');

        $streamers = Streamer::where('is_monitoring', true)
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        foreach ($streamers as $streamer) {
            $this->checkStreamer($streamer);
        }

        $this->info('监控完成');
        return Command::SUCCESS;
    }

    protected function checkStreamer(Streamer $streamer)
    {
        try {
            $wasLive = $streamer->is_live;
            $result = $this->douyinService->checkLiveStatus($streamer);

            $isLive = $result['success'] ? $result['is_live'] : false;

            $streamer->update([
                'is_live' => $isLive,
                'last_check_time' => now(),
            ]);

            if ($isLive && !$wasLive) {
                $streamer->update(['last_live_time' => now()]);
                $this->sendNotification($streamer);
            }

            MonitorLog::create([
                'user_id' => $streamer->user_id,
                'streamer_id' => $streamer->id,
                'was_live' => $wasLive,
                'is_live' => $isLive,
                'notification_sent' => $isLive && !$wasLive,
                'response_data' => json_encode($result),
            ]);

            $this->info("主播 {$streamer->name} 检测完成: " . ($isLive ? '直播中' : '未直播'));
        } catch (\Exception $e) {
            Log::error('检测主播失败', [
                'streamer_id' => $streamer->id,
                'error' => $e->getMessage(),
            ]);
            $this->error("检测主播 {$streamer->name} 失败: " . $e->getMessage());
        }
    }

    protected function sendNotification(Streamer $streamer)
    {
        try {
            $user = $streamer->user;
            if (!$user->phone) {
                Log::warning('用户未设置手机号', ['user_id' => $user->id]);
                return;
            }

            $result = $this->smsService->sendLiveNotification(
                $user->phone,
                $streamer->name,
                $streamer->douyin_url
            );

            if ($result['success']) {
                $this->info("已发送开播通知给 {$user->phone}");
            } else {
                $this->error("发送通知失败: {$result['message']}");
            }
        } catch (\Exception $e) {
            Log::error('发送通知失败', [
                'streamer_id' => $streamer->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}