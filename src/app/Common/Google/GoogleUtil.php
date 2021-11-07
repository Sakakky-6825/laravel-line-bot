<?php
namespace App\Common\Google;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleUtil
{
  /**
   * Google Calendar IDを取得
   */
  private static function getGoogleCalendarID()
  {
    $google_calendar_id = env('GOOGLE_CALENDAR_ID');
    return $google_calendar_id;
  }

  /**
   * GoogleClientを取得
   */
  private static function getGoogleClient()
  {
    $client = new Google_Client();

    // client name set
    $client->setApplicationName('Google Calendar API plus Laravel');

    //権限の指定
    $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);

    // config setting
    $client->setAuthConfig(storage_path(env('GOOGLE_AUTH_CONFIG_PATH')));

    return $client;
  }

  /**
   * Google Calendarへ予定を登録
   */
  public static function saveGoogleCalendarSchedule()
  {
    $client = self::getGoogleClient();

    $service = new Google_Service_Calendar($client);

    // GoogleCalendarID取得
    $calendar_id = self::getGoogleCalendarID();

    // Calendar Eventの取得
    $event = self::createGoogleCalendarEvent(array());

    $service->events->insert($calendar_id, $event);
    Log::info('カレンダー登録しました。');
  }

  /**
   * Google Calendar Eventの生成
   */
  private static function createGoogleCalendarEvent($params)
  {
    // 日付
    // $date = new Carbon($params['event_date']);
    $date = new Carbon('now');

    // Event生成
    $event = new Google_Service_Calendar_Event(array(
      'summary' =>  'テスト作成イベント',
      'start'   =>  array(
        // 開始時刻
        'dateTime'  =>  $date,
        'timeZone'  =>  env('TIME_ZONE'),
      ),
      'end'     =>  array(
        // 終了時刻
        'dateTime'  =>  $date->addHour(),
        'timeZone'  =>  env('TIME_ZONE'),
      )
    ));

    return $event;
  }
}