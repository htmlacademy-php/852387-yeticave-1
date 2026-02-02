<?php

/**
 * @var string $title имя странице
 * @var int $is_auth рандомно число 1 или 0
 * @var string $user_name имя авторизованного пользователя
 * @var array<array{name: string, code: string} $categories список категорий лотов
 * @var array<int,array{name: string, category: string, price: int, url: string, date_end: string} $lots массив с параметрами лотов
 * @var array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 * @var string $main_content HTML-код - контент страницы
 */

// лимит кол-ва элементов
const LIMIT_ITEMS = 10;

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Татьяна';
$categories = [];
$lots = [];
$bets = [];
$main_content = '';
