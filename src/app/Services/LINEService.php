<?php

namespace App\Services;

use Illuminate\Http\Request;
// LINE
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINEUtil;
// logs
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LINEService
{
  public function sendMessage(Request $request, $params)
  {
    // webhookの処理
    $events = LINEUtil::getEventsByWebhook($request);
    // log
    Log::info('_____LINE Message Event_____');
    Log::info($events);

    foreach ($events as $event) {
      // eventがmessageの時
      if ($event instanceof TextMessage) {
        // $bot 準備
        $bot = LINEUtil::prepareToSendMessage();

        // relpy_token
        $reply_token = LINEUtil::getReplyToken($event);

        $message = "予定登録しました！";
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

  /**
   * LINE Messageの解析
   * 
   */
  public function analysisLINEMessage($request)
  {
    $events = LINEUtil::getEventsByWebhook($request);
    // 返却用の配列作成
    $event_array = array(
      'event_date'  =>  '',
      'event_title' =>  '',
    );

    foreach ($events as $event) {
      if ($event instanceof TextMessage) {
        // 受信したメッセージのテキストを取得
        $receive_message = $event->getText();
        // 受信したメッセージを改行で分割
        $str = str_replace(array("\r\n", "\r", "\n"), "\n", $receive_message);
        $texts = explode("\n", $str);
        Log::info($receive_message);
        Log::info($texts);
        
        $d = $texts[0];
        if (empty(preg_match("/^[0-9]{4}年[0-9]{2}/", $texts[0]))) {
          Log::info('_____年が存在しない時_____');
          $date_time = Carbon::now();
          $d = $date_time->year."年".$texts[0];
        }
        
        // 年月日を変換、曜日があれば除去
        $d1 = $d;
        $d2 = preg_replace("/\(.*?\)|（.*?）/u", '', $d1);
        $d3 = str_replace(array("年", "月"), "-", $d2);
        $date_text = str_replace(array("日"), "", $d3);
        Log::info($date_text);

        // 日付変換
        try {
          $date = new Carbon($date_text);
        }
        catch(\Exception $e) {
          // 処理不可メッセージ送信
          $this->sendErrorMessage($event);
          Log::error($e);
          return;
        }
        $event_array['event_date'] = $date_text;
        $event_array['event_title'] = $texts[1];
        
        Log::info($date);
      }
    }

    return $event_array;
  }

  /**
   * 
   */
  public function sendErrorMessage($event)
  {
    // $bot 準備
    $bot = LINEUtil::prepareToSendMessage();

    // relpy_token
    $reply_token = LINEUtil::getReplyToken($event);

    $message = "ごめんなさい。受信したメッセージでは予定登録できません。";
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