<?php
declare(strict_types=1);

require_once('init.php');
require_once('data.php');
require_once('utils/helpers.php');
require_once('models/lots.php');
require_once('utils/date-time.php');
require_once('utils/price.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории лотов из БД
 * @var ?array<int,array{id: int, author_id: int, date_end: string, name: string,
 *          img_url: string, decription: string, price_start: int, step_bet: int, cat_name: string,
 *          cost: int} $lots массив данных лотов из БД
 * @var ?string $cat_name название категории
 * @var ?int[] $pages массив с номерами страниц
 * @var int $pages_count количество страниц
 * @var int $cur_page текущая (открытая) страница
 * @var ?int $items_count количество лотов по поисковому запросу
 * @var string $content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
 */

const ITEMS_PER_PAGE = 2;
const RUB_UPPER_CASE = 'RUB_UPPER_CASE';
const TAB = 'category';
const PATH = 'all-lots.php';

$title = 'Все лоты в категории';
$cat_id = (int)filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);

if ($cat_id) {
    $cur_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
    $items_count = count_lots_by_category($connect, $cat_id);
    [$pages_count, $offset, $pages] = get_data_pagination((int)$cur_page, $items_count, ITEMS_PER_PAGE);
    $lots = get_lots_by_category($connect, $cat_id, ITEMS_PER_PAGE, $offset);
    $cat_name = get_category_name($connect, $cat_id);
    if (!$cat_name) {
        http_response_code(404);
        $path = '404.php';
    }
}

$content = include_template('all-lots.php', [
    'categories' => $categories,
    'cat_name' => $cat_name,
    'lots' => $lots,
    'count_lots' => $items_count,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'tab' => TAB,
    'path' => PATH,
    'cat_id' => $cat_id,
    'symbol' => RUB_UPPER_CASE,
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
    'cat_name' => $cat_name,
]);

print($layout);
