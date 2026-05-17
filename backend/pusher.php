<?php

require_once __DIR__ . '/../vendor/autoload.php';

$options = [
  'cluster' => $_ENV['PUSHER_APP_CLUSTER'],
  'useTLS' => true
];

$pusher = new Pusher\Pusher(
  $_ENV['PUSHER_APP_KEY'],
  $_ENV['PUSHER_APP_SECRET'],
  $_ENV['PUSHER_APP_ID'],
  $options
);