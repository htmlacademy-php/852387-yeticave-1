<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('models/lots.php');
require_once ('utils/date-time.php');
require_once ('utils/price.php');

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var int $is_auth
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array $lots
 * @var ?int $pages
 * @var ?int $pages_count
 * @var ?int $cur_page
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
 */

$ITEMS_PER_PAGE = 3;
const RUB_UPPER_CASE = 'RUB_UPPER_CASE';

$title = 'Поиск лотов';

$search = trim($_GET['search']) ?? '';
if ($search) {
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 6;
    //узнаем общее число лотов
    $items_count = count_lots_by_search($connect, $search);
    [$pages_count, $offset, $pages] = get_data_pagination($cur_page, $items_count, $ITEMS_PER_PAGE);

    $lots = search_lots($connect, $search, $ITEMS_PER_PAGE, $offset);
}

$content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'symbol' => RUB_UPPER_CASE
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout);
