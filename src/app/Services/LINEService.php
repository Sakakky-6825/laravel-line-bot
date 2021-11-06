<?php

namespace App\Services;

use Illuminate\Http\Request;
// LINE
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Util;
// logs
use Illuminate\Support\Facades\Log;

class LINEService
{
  public function sendMessage(Request $request)
  {
    // webhookの処理
    $events = Util::getEventsByWebhook($request);
    // log
    Log::info($events);

    foreach ($events as $event) {
      // eventがmessageの時
      if ($event instanceof TextMessage) {
        // $bot 準備
        $bot = Util::prepareToSendMessage();

        // relpy_token
        $reply_token = Util::getReplyToken($event);

        $message = "Hello World!";
        $response = $bot->replyText($reply_token, $message);

        if ($response->isSucceeded()) {
          Log::info('返信成功');
          return;
        }
        else {
          Log::error('返信失敗');
          Log::error($response->getRawBody());
          return;
        }
      }
    }
  }
}