<?php

/**
 * @var int $is_auth рандомно число 1 или 0
 * @var string $user_name имя пользователя
 * @var string[] $categories массив названий категорий
 * @var array<int,array{name: string, category: string, price: int, url: string} $lots массив с параметрами лотов
 */

$is_auth = rand(0, 1);

$user_name = 'Татьяна';

$categories = [
    'Доски и лыжи',
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
        'url' => '/img/lot-1.jpg'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'url' => '/img/lot-2.jpg'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'url' => '/img/lot-3.jpg'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charcoal',
        'category' => 'Ботинки',
        'price' => 10999,
        'url' => '/img/lot-4.jpg'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charcoal',
        'category' => 'Одежда',
        'price' => 7500,
        'url' => '/img/lot-5.jpg'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'url' => '/img/lot-6.jpg'
    ]
];
