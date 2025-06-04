<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once ('init.php');

const LIMIT_ITEMS = 10;

/**
 * @var string $user_name
 * @var boolean|object $connect
 * @var string $user_name
 * @var string[] $categories
 * @var array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lot
 */

$user_name = 'Татьяна';
$is_auth = rand(0, 1);
$categories = [];
$lot = [];
$page_content = '';

if (!$connect) {
    die(mysqli_connect_error());
}
// выполнение запроса на список категорий
$sql = 'SELECT *  FROM categories LIMIT ?';

$categories = getItems($connect, $sql, LIMIT_ITEMS);

if (!isset($_GET['id'])) {
    http_response_code(404);
    $page_content = include_template('404.php', [
        'categories' => $categories
    ]);
} else {
    $id = mysqli_real_escape_string($connect, $_GET['id']);

    $sql = 'SELECT l.id,
        l.user_id "autor_id",
        l.date_end,
        l.name "lot_name",
        l.img_url,
        l.description,
        price "price_start",
        img_url, c.name "cat_name" FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
                              WHERE l.id = "%s"';

    $sql = sprintf($sql, $id);
    $result = mysqli_query($connect, $sql);
    $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (!isset($lot)) {

        http_response_code(404);
        $page_content = include_template('404.php', [
            'categories' => $categories
        ]);;
    } else {

        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot,
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);