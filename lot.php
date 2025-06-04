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
 * @var array{id: string, author_id: string, date_end: string, lot_name: string, cat_name: string, price_start: string, img_url: string, description: string, step_bet: string} $lot
 * * все данные по ID лота из БД
 * @var array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 *
 */

$user_name = 'Татьяна';
$is_auth = rand(0, 1);
$categories = [];
$lot = [];
$bets = [];
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
        l.price "price_start",
        l.step_bet,
        img_url, c.name "cat_name" FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
                              WHERE l.id = %s';

    $sql = sprintf($sql, $id);
    $result = mysqli_query($connect, $sql);
    $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (!isset($lot)) {

        http_response_code(404);
        $page_content = include_template('404.php', [
            'categories' => $categories
        ]);;
    } else {

        $sql = 'SELECT user_id "customer_id",
                    lot_id,
                    date_add,
                    cost FROM bets
         WHERE lot_id = %s
         ORDER BY bets.date_add
         LIMIT %s';

        $sql = sprintf($sql, $id, LIMIT_ITEMS);
        $result = mysqli_query($connect, $sql);
        $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'bets' => $bets
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