<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

use App\Services\LINEService;
use App\Services\GoogleService;

use Illuminate\Support\Facades\Log;

class LineMessagerController extends Controller
{
    //
    public function sendMessage(LINEService $line_service, GoogleService $google_service, Request $request)
    {
        Log::info('_____処理開始_____');
        Log::info('Requestの中身');
        Log::info($request);
        // LINE Message 解析
        $receive_message = $line_service->analysisLINEMessage($request);

        // Google Calendar Regist
        $response = $google_service->saveGoogleCalendarSchedule($receive_message);

        if ($response->getStatus() === 'confirmed') {
            // LINE Message Send
            $line_service->sendMessage($request, $receive_message);
        }
        Log::info('_____処理終了_____');
    }
}
