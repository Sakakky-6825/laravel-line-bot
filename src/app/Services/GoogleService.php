<?php

namespace App\Services;

use Illuminate\Http\Request;

// logs
use Illuminate\Support\Facades\Log;
use GoogleUtil;

class GoogleService
{
  public function saveGoogleCalendarSchedule(Request $request)
  {
    // 登録
    GoogleUtil::saveGoogleCalendarSchedule();
  }
}