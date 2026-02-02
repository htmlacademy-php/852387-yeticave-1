<?php

/**
 * @var string $title имя странице
 * @var int $is_auth рандомно число 1 или 0
 * @var string $user_name имя пользователя
 * @var string[] $categories массив названий категорий
 * @var array<int,array{name: string, category: string, price: int, url: string, date_end: string} $lots массив с параметрами лотов
 * @var string $main_content HTML-код - контент страницы
 */

// лимит кол-ва элементов
const LIMIT_ITEMS = 10;

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Татьяна';
$categories = [];
$lots = [];
$main_content = '';
