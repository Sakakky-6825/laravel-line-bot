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
        // LINE Message Send
        $line_service->sendMessage($request);

        // Google Calendar Regist
        $google_service->saveGoogleCalendarSchedule($request);
        Log::info('_____処理終了_____');
    }
}
