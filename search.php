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
 * @var int $pages
 * @var int $pages_count
 * @var int $cur_page
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 */

$title = 'Поиск лотов';

$search = $_GET['search'] ?? '';
if ($search) {
    $lots = search_lots($connect, $search);
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
