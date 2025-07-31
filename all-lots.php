<?php
declare(strict_types=1);

require_once('init.php');
require_once('models/lots.php');
require_once('utilities/date-time.php');
require_once('utilities/helpers.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var array<int,array{id: int, author_id: int, date_end: string, lot_name: string, img_url: string,
 *     decription: string, price_start: int, step_bet: int, cat_name: string, cost: int} массив данных лотов из БД
 * @var string $cat_name название категории
 * @var int[] $pages массив с номерами страниц
 * @var int $pages_count количество страниц
 * @var int $cur_page текущая (открытая) страница
 * @var int $items_count кол-во лотов по значению поиска
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 */

const ITEMS_PER_PAGE = 9;
const RUB_UPPER_CASE = 'RUB_UPPER_CASE';
const TAB = 'category';
const PATH = 'all-lots.php';

$title = 'Все лоты в категории';
$cat_name = null;
$items_count = null;
$path = 'all-lots.php';
$lots = null;
$pages = null;
$pages_count = 0;
$cur_page = 1;

$cat_id = htmlspecialchars(trim($_GET['category'] ?? '')) ?? null;

if ($cat_id) {
    $cur_page = $_GET['page'] ?? 1;
    $items_count = count_lots_by_category($connect, intval($cat_id));
    [$pages_count, $offset, $pages] = get_data_pagination((int)$cur_page, $items_count, ITEMS_PER_PAGE);
    $lots = get_lots_by_category($connect, intval($cat_id), ITEMS_PER_PAGE, $offset);
    $cat_name = get_category_name($connect, intval($cat_id));
    if (!$cat_name) {
        http_response_code(404);
        $path = '404.php';
    }
}

$page_content = include_template($path, [
    'categories' => $categories,
    'cat_name' => $cat_name,
    'lots' => $lots,
    'count_lots' => $items_count,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => (int)$cur_page,
    'tab' => TAB,
    'path' => PATH,
    'cat_id' => $cat_id,
    'symbol' => RUB_UPPER_CASE,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);
