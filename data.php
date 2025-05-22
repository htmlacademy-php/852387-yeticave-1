<?php
/**
 * @var string $user_name
 * @var string[] $categories
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 */

$user_name = 'Татьяна';

$categories = [
    "Доски и лыжи",
    'Крепления',
    'Ботинки',
    'Одежда',
    'Инструменты',
    'Разное'
];

$lots = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'img_url' => '/img/lot-1.jpg',
        'date_end' => '2025-05-21',
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'img_url' => '/img/lot-2.jpg',
        'date_end' => '2025-05-25',
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'img_url' => '/img/lot-3.jpg',
        'date_end' => '2025-06-24',
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'img_url' => '/img/lot-4.jpg',
        'date_end' => '2025-04-23',
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'img_url' => '/img/lot-5.jpg',
        'date_end' => '2025-05-23',
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'img_url' => '/img/lot-6.jpg',
        'date_end' => '2025-05-30',
    ],
];
