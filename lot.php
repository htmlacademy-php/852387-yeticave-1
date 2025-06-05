<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once ('init.php');
require_once ('model/categories.php');
require_once ('model/lots.php');
require_once ('model/bets.php');

/**
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|object $connect mysqli Ресурс соединения
 * @var int $is_auth
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array{id: string, author_id: string, date_end: string, lot_name: string, cat_name: string, price_start: string, img_url: string, description: string, step_bet: string} $lot
 * * все данные по ID лота из БД
 * @var ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 */

if (!$connect) {
    die(mysqli_connect_error());
}
// выполнение запроса на список категорий
$categories = getCategories($connect);

if (!isset($_GET['id'])) {
    http_response_code(404);
    $path = '404.php';
} else {
    $id = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!$id) {
        http_response_code(404);
        $path = '404.php';
    } else {
        $lot = getLotById($connect, $id);
        if (!$lot) {
            http_response_code(404);
            $path = '404.php';
        } else {
            $bets = getBetsById($connect, $id);
            $path = 'lot.php';
        }
    }
}

$page_content = include_template($path, [
    'categories' => $categories,
    'lot' => $lot,
    'bets' => $bets
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);