<?php
declare(strict_types=1);

require_once ('utils/helpers.php');
require_once ('data.php');
require_once ('init.php');
require_once ('utils/db.php');

/**
 * @var string $title имя странице
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var int $is_auth рандомно число 1 или 0
 * @var string $user_name имя авторизованного пользователя
 * @var array<array{name: string, code: string} $categories список категорий лотов
 * @var array{id: int, author_id: int, date_add: string, name: string, description: string, img_url: string, price_start: int, step_bet: int, cat_name: string} $lot все данные по ID лота из БД
 * @var array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 * @var string $main_content HTML-код - контент страницы
 * @var string $page весь HTML-код страницы с подвалом и шапкой
 */

if (!$connect) {
    die(mysqli_connect_error());
}
// выполнение запроса на список категорий
$sql = 'SELECT *  FROM categories LIMIT ?';
$categories = get_items($connect, $sql, LIMIT_ITEMS);

if (!isset($_GET['id'])) {

    http_response_code(404);
    $main_content = include_template('404.php', ['categories' => $categories ]);

} else {

    $id = mysqli_real_escape_string($connect, $_GET['id']);


    $sql = 'SELECT l.id,
           l.user_id "author_id",
           l.date_end,
           l.name,
           l.description,
           l.img_url,
           price "price_start",
           l.step_bet,
           c.name "cat_name" FROM lots l
               INNER JOIN categories c ON l.cat_id = c.id
                                      WHERE l.id = %s';

    $sql = sprintf($sql, $id);
    $result = mysqli_query($connect, $sql);
    $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (!isset($lot)) {

        http_response_code(404);
        $main_content = include_template('404.php', [
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

        $main_content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'bets' => $bets
        ]);
    }
}

$page = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($page);
