<?php

/**
 * @var string $title заголовок страницы сайта
 * @var ?array<int,array{name: string, category: string, price: int, url: string, date_end: string} $lots массив с параметрами лотов
 * @var ?array{id: int, author_id: int, date_add: string, name: string, description: string, img_url: string, price_start: int, step_bet: int, cat_name: string} $lot все данные по ID лота из БД
 * @var ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 * @var ?array<int,array{id: int, date_add: string, name: string, email: string, password: string, contact: string} $users массив с параметрами по всем users из БД
 * @var ?array $form заполненные пользователем поля формы
 * @var string $content HTML-код - контент страницы
 */

// лимит кол-ва элементов
const LIMIT_ITEMS = 10;

$title = 'Главная';
$lots = [];
$lot = [];
$bets = [];
$form = [];
$users = [];
$content = '';
