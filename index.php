<?php
declare(strict_types=1);

require_once ('utils/helpers.php');
require_once ('data.php');

/**
 * @var string $title имя странице
 * @var int $is_auth рандомно число 1 или 0
 * @var string $user_name имя пользователя
 * @var string[] $categories массив названий категорий
 * @var array<int,array{name: string, category: string, price: int, url: string, date_end: string} $lots массив с параметрами лотов
 * @var string $main_content HTML-код - контент страницы
 * @var string $page весь HTML-код страницы с подвалом и шапкой
 */

$title = 'Главная';

$main_content = include_template('main.php', [
        'categories' => $categories,
        'lots' => $lots,
]);
$page = include_template('layout.php', [
        'title' => $title,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'categories' => $categories,
        'main_content' => $main_content
]);

print($page);