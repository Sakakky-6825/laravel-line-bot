<?php

namespace App\Services;

use Illuminate\Http\Request;

// logs
use Illuminate\Support\Facades\Log;
use GoogleUtil;
use Google_Service_Calendar;

class GoogleService
{
  public function saveGoogleCalendarSchedule($params)
  {
    // 登録
    $response = $this->saveSchedule($params);

    return $response;
  }

  /**
   * Google Calendarへ予定を登録
   */
  private function saveSchedule($params)
  {
    $client = GoogleUtil::getGoogleClient();

    $service = new Google_Service_Calendar($client);

    // GoogleCalendarID取得
    $calendar_id = GoogleUtil::getGoogleCalendarID();

    // Calendar Eventの取得
    $event = GoogleUtil::createGoogleCalendarEvent($params);

    $response = $service->events->insert($calendar_id, $event);
    Log::info('カレンダー登録しました。');
    Log::info($response->getStatus());

    return $response;
  }
}