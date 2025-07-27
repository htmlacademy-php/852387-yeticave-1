<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('models/lots.php');
require_once ('models/categories.php');
require_once ('utilities/date-time.php');
require_once ('utilities/helpers.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array $lots
 * @var string $cat_name
 * @var int $pages
 * @var int $pages_count
 * @var int $cur_page
 * @var ?int $items_count
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 */

const ITEMS_PER_PAGE = 3;
const RUB_UPPER_CASE = 'RUB_UPPER_CASE';

$title = 'Все лоты в категории';

$cat_id = $_GET['category'] ?? null;

if ($cat_id) {

    $cur_page = $_GET['page'] ?? 1;
//узнаем общее число лотов в категории
    $items_count = count_lots_by_category($connect, $cat_id);

    [$pages_count, $offset, $pages] = get_data_pagination($cur_page, $items_count, ITEMS_PER_PAGE);

    $lots = get_lots_by_category($connect, $cat_id, ITEMS_PER_PAGE, $offset);

    $cat_name = get_category_name($connect ,$cat_id);
}

$page_content = include_template('all-lots.php', [
    'categories' => $categories,
    'cat_name' => $cat_name,
    'lots' => $lots,
    'count_lots' => $items_count,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'symbol' => RUB_UPPER_CASE,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);
