<?php

namespace App\Services;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\SmsClient;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $secretId;
    protected $secretKey;
    protected $appId;
    protected $signName;
    protected $templateId;

    public function __construct()
    {
        $this->secretId = env('TENCENT_SMS_SECRET_ID');
        $this->secretKey = env('TENCENT_SMS_SECRET_KEY');
        $this->appId = env('TENCENT_SMS_APP_ID');
        $this->signName = env('TENCENT_SMS_SIGN_NAME');
        $this->templateId = env('TENCENT_SMS_TEMPLATE_ID');
    }

    public function sendLiveNotification(string $phone, string $streamerName, string $streamerUrl): array
    {
        try {
            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("sms.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);

            $client = new SmsClient($cred, "ap-guangzhou", $clientProfile);

            $req = new SendSmsRequest();
            $req->PhoneNumberSet = ["+86" . $phone];
            $req->TemplateId = $this->templateId;
            $req->SignName = $this->signName;
            $req->TemplateParamSet = [$streamerName, $streamerUrl];

            $resp = $client->SendSms($req);

            Log::info('SMS sent successfully', [
                'phone' => $phone,
                'streamer' => $streamerName,
                'response' => json_encode($resp->serialize()),
            ]);

            return [
                'success' => true,
                'message' => '短信发送成功',
                'data' => $resp->serialize(),
            ];
        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'phone' => $phone,
                'streamer' => $streamerName,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => '短信发送失败: ' . $e->getMessage(),
            ];
        }
    }

    public function sendTestMessage(string $phone): array
    {
        try {
            $cred = new Credential($this->secretId, $this->secretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("sms.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);

            $client = new SmsClient($cred, "ap-guangzhou", $clientProfile);

            $req = new SendSmsRequest();
            $req->PhoneNumberSet = ["+86" . $phone];
            $req->TemplateId = $this->templateId;
            $req->SignName = $this->signName;
            $req->TemplateParamSet = ["测试主播", "https://www.douyin.com/test"];

            $resp = $client->SendSms($req);

            return [
                'success' => true,
                'message' => '测试短信发送成功',
                'data' => $resp->serialize(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => '测试短信发送失败: ' . $e->getMessage(),
            ];
        }
    }
}