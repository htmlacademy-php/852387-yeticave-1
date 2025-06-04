<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once ('init.php');

const LIMIT_ITEMS = 10;

/**
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|object $connect mysqli Ресурс соединения
 * @var int $is_auth
 * @var array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * * все новые лота из БД
 */

$user_name = 'Татьяна';
$is_auth = rand(0, 1);
$categories = [];
$lots = [];
$page_content = '';

if (!$connect) {
    die(mysqli_connect_error());
}
    // выполнение запроса на список категорий
$sql = 'SELECT *  FROM categories LIMIT ?';

$categories = getItems($connect, $sql, LIMIT_ITEMS);
// выполнение запроса на список новых лотов
$sql = 'SELECT l.id,
       l.date_end,
       l.name "lot_name",
       price "price_start",
       img_url,
       MAX(b.cost) "cost",
       c.name "cat_name" FROM lots l
           LEFT JOIN bets b ON b.lot_id = l.id
           INNER JOIN categories c ON l.cat_id = c.id
                         WHERE l.date_end > DATE(NOW())
                         GROUP BY l.id
                         ORDER BY l.date_add DESC LIMIT ?';

$lots = getItems($connect, $sql, LIMIT_ITEMS);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);
