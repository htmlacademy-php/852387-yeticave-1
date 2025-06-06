<?php

declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once('init.php');
require_once('models/categories.php');
//require_once('models/lots.php');

/**
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|object $connect mysqli Ресурс соединения
 * @var int $is_auth
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * * все новые лота из БД
 */

if (!$connect) {
    die(mysqli_connect_error());
}
// выполнение запроса на список категорий
$categories = getCategories($connect);

$page_content = include_template('add.php', [
    'categories' => $categories,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);
