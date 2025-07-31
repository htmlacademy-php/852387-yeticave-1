<?php
declare(strict_types=1);

require_once('init.php');
require_once('models/lots.php');
require_once('utilities/date-time.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array<int,array{id: int, author_id: int, date_end: string, lot_name: string,
 *     img_url: string, description: string, price_start: int, step_bet: int,
 *     cat_name: string} $lots все лоты из БД по заданному поиску
 * @var int[] $pages массив с номерами страниц
 * @var int $pages_count количество страниц
 * @var int $cur_page текущая (открытая) страница
 * @var string $search значение поиска
 * @var int $items_count кол-во лотов по значению поиска
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 */

const ITEMS_PER_PAGE = 9;
const RUB_UPPER_CASE = 'RUB_UPPER_CASE';
const TAB = 'search';
const PATH = 'search.php';

$title = 'Поиск лотов';

$search = htmlspecialchars(trim($_GET['search'] ?? '')) ?? null;

if ($search) {

    $cur_page = $_GET['page'] ?? 1;
    $items_count = count_lots_by_search($connect, $search);
    [$pages_count, $offset, $pages] = get_data_pagination((int)$cur_page, $items_count, ITEMS_PER_PAGE);
    $lots = search_lots($connect, $search, ITEMS_PER_PAGE, $offset);

}

$page_content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'search' => $search,
    'tab' => TAB,
    'path' => PATH,
    'symbol' => RUB_UPPER_CASE
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);