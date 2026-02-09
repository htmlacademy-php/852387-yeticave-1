<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('models/lots.php');
require_once ('utils/date-time.php');
require_once ('utils/price.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var array<int,array{id: int, author_id: int, date_end: string, name: string,
 *      img_url: string, description: string, price_start: int, step_bet: int,
 *      cat_name: string} $lots все лоты из БД по заданному поиску
 * @var ?int[] $pages массив с номерами страниц
 * @var int $pages_count количество страниц
 * @var int $cur_page текущая (открытая) страница
 * @var ?int $items_count количество лотов по поисковому запросу
 * @var ?string $cat_name название категории
 * @var string $search строка которую необходимо найти
 * @var string $content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
 */

const ITEMS_PER_PAGE = 3;
const RUB_UPPER_CASE = 'RUB_UPPER_CASE';
const TAB = 'search';
const PATH = 'search.php';

$title = 'Поиск лотов';
$search = htmlspecialchars(trim($_GET['search'])) ?? '';

if ($search) {
    $cur_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
    $items_count = count_lots_by_search($connect, $search);
    [$pages_count, $offset, $pages] = get_data_pagination((int)$cur_page, $items_count, ITEMS_PER_PAGE);
    $lots = search_lots($connect, $search, ITEMS_PER_PAGE, $offset);
}

$content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'search' => $search,
    'cat_name' => $cat_name,
    'tab' => TAB,
    'path' => PATH,
    'symbol' => RUB_UPPER_CASE
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
    'cat_name' => $cat_name
]);

print($layout);
