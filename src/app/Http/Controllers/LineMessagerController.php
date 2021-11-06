<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

use App\Services\LINEService;

class LineMessagerController extends Controller
{
    //
    public function sendMessage(LINEService $line_service,Request $request)
    {
        $line_service->sendMessage($request);
    }
}
