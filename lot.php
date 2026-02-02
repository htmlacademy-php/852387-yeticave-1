<?php
declare(strict_types=1);

require_once ('utils/helpers.php');
require_once ('data.php');
require_once ('init.php');
require_once ('utils/db.php');
require_once ('models/categories.php');
require_once ('models/lots.php');
require_once ('models/bets.php');

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

$categories = get_categories($connect);

if (!isset($_GET['id'])) {

    http_response_code(404);
    $path = '404.php';

} else {

    $id = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!$id) {
        http_response_code(404);
        $path = '404.php';

    } else {

        $lot = get_lot_by_id($connect, $id);
        if (!$lot) {

            http_response_code(404);
            $path = '404.php';

        } else {

            $bets = get_bets_by_id($connect, $id);
            $path = 'lot.php';

        }
    }
}

$main_content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot,
    'bets' => $bets
]);

$page = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($page);
