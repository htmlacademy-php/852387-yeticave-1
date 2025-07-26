<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('utilities/helpers.php');
require_once ('utilities/date-time.php');
require_once ('init.php');
require_once ('models/categories.php');
require_once ('models/lots.php');
require_once ('models/bets.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array{id: string, author_id: string, date_end: string, lot_name: string, cat_name: string, price_start: string, img_url: string, description: string, step_bet: string} $lot
 * * все данные по ID лота из БД
 * @var ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 */

$title = 'Страница лота';

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

$page_content = include_template($path, [
    'categories' => $categories,
    'lot' => $lot,
    'bets' => $bets
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);