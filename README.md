# GoogleCalendarに予定を登録するLINE Bot

## 概要
LINE Botに日付と予定タイトルを入力するとGoogle Calendarに予定を登録してくれる。

## 初期設定（ローカルで動かす方法）
1. リポジトリをクローン後、dockerビルドする。
```
> docker-compose build
```

2. コンテナ立ち上げる。
```
> docker-compose up -d
```

3. LINE Developerに登録し、環境変数に設定する。
以下参考に進める。
https://codeeee.net/posts/laravel-line-messaging-api#line%E5%81%B4%E3%81%AE%E6%BA%96%E5%82%99

