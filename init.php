<?php
declare(strict_types=1);

session_start();

 define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
 define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

require_once ('utilities/helpers.php');
require_once ('models/categories.php');
$db = require_once('config.php');

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var int $is_auth
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * * все новые лота из БД
 */

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, 'utf8');

if (!$connect) {
    die(mysqli_connect_error());
}

// начальные данные
$title = 'Главная';
$user_name = 'Татьяна';
$is_auth = rand(0, 1);
// выполнение запроса на список категорий
$categories = get_categories($connect);
$lots = null;
$bets = null;
$lot = null;
$page_content = '';
