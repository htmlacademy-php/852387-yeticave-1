<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('models/lots.php');
require_once ('utils/date-time.php');

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
 */

$title = 'Поиск лотов';

$search = trim($_GET['search']) ?? '';
if ($search) {
    $cur_page = $_GET['page'] ?? 1;
    $page_items = 6;
    //узнаем общее число лотов
    $items_count = count_lots($connect);
    //считаем кол-во страниц и смещение
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    //заполняем массив номерами всех страниц
    $pages = range(1, $pages_count);

    $lots = search_lots($connect, $search, $page_items, $offset);
}

$content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);
