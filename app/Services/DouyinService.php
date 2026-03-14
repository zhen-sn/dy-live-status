<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Streamer;

class DouyinService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ],
        ]);
    }

    public function parseDouyinUrl(string $url): ?string
    {
        try {
            $response = $this->client->get($url, [
                'allow_redirects' => [
                    'track_redirects' => true,
                ],
            ]);

            $redirectUrl = $response->getHeader(\GuzzleHttp\RedirectMiddleware::HISTORY_HEADER);
            if (!empty($redirectUrl)) {
                $finalUrl = end($redirectUrl);
                return $this->extractDouyinId($finalUrl);
            }

            return $this->extractDouyinId($url);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function extractDouyinId(string $url): ?string
    {
        if (preg_match('/user\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('/\/share\/user\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function checkLiveStatus(Streamer $streamer): array
    {
        try {
            if (!$streamer->douyin_id) {
                $douyinId = $this->parseDouyinUrl($streamer->douyin_url);
                if ($douyinId) {
                    $streamer->update(['douyin_id' => $douyinId]);
                } else {
                    return [
                        'success' => false,
                        'is_live' => false,
                        'message' => '无法解析抖音ID',
                    ];
                }
            }

            $apiUrl = "https://www.douyin.com/user/{$streamer->douyin_id}";
            $response = $this->client->get($apiUrl);
            $html = $response->getBody()->getContents();

            $isLive = $this->detectLiveStatus($html);

            return [
                'success' => true,
                'is_live' => $isLive,
                'message' => $isLive ? '直播中' : '未直播',
                'data' => [
                    'html_length' => strlen($html),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'is_live' => false,
                'message' => '检测失败: ' . $e->getMessage(),
            ];
        }
    }

    protected function detectLiveStatus(string $html): bool
    {
        $liveIndicators = [
            'live',
            '直播中',
            '正在直播',
            'isLive',
            'live_status',
            '直播状态',
        ];

        foreach ($liveIndicators as $indicator) {
            if (stripos($html, $indicator) !== false) {
                return true;
            }
        }

        if (preg_match('/"isLive":\s*true/i', $html)) {
            return true;
        }

        if (preg_match('/"liveStatus":\s*1/i', $html)) {
            return true;
        }

        return false;
    }
}