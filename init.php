<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

/**
 * @var array{host: string, user: string, pass: string, db: string} $db параметры для подключения БД
 * @var bool|mysqli|object $connect ресурс соединения с сервером БД
 */

session_start();

$db = require('config-db.php');

$connect = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['database']);
mysqli_set_charset($connect, 'utf8');

if (!$connect) {
    die(mysqli_connect_error());
}
