<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('utils/helpers.php');
require_once ('utils/db.php');
require_once ('models/lots.php');
require_once ('models/bets.php');
require_once ('utils/date-time.php');
require_once ('utils/price.php');

/**
 * @var string $title заголовок страницы сайта
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var array{id: int, author_id: int, date_add: string, name: string, description: string, img_url: string, price_start: int, step_bet: int, cat_name: string} $lot все данные по ID лота из БД
 * @var array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 * @var string $content HTML-код - контент страницы
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
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

$content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot,
    'bets' => $bets
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout);
