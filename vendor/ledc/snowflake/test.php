<?php

use Ledc\Snowflake\Snowflake;
use Ledc\Snowflake\Sonyflake;

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');
$snowflake = new Snowflake;
$sonyflake = new Sonyflake;
$i = 100;
while ($i--) {
    echo sprintf('%d Twitter雪花id' . PHP_EOL, $snowflake->id());
    echo sprintf('%d Sony雪花id' . PHP_EOL, $sonyflake->id());
}
