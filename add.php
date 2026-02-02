<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('init.php');
require_once ('utils/helpers.php');
require_once ('models/categories.php');

/**
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var int $is_auth рандомно число 1 или 0
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var string $main_content HTML-код - контент страницы
 * @var string $page весь HTML-код страницы с подвалом и шапкой
 */

$title = 'Добавление лота';

if (!$connect) {
    die(mysqli_connect_error());
}
// выполнение запроса на список категорий
$categories = get_categories($connect);

$main_content = include_template('add.php', [
    'categories' => $categories,
]);

$page = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($page);
