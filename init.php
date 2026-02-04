<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

session_start();

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

$db = require_once('config-db.php');
require_once ('models/categories.php');

/**
 * @var array{host: string, user: string, pass: string, db: string} $db параметры для подключения БД
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var ?array<array{id: int, name: string, code: string} $categories список категорий лотов
*/

$connect = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['database']);
mysqli_set_charset($connect, 'utf8');

if (!$connect) {
    die(mysqli_connect_error());
}

// выполнение запроса на список категорий
$categories = get_categories($connect);
$errors = null;
