<?php
declare(strict_types=1);

const CURRENCY = [
    'RUB_UPPER_CASE' => '₽',
    'RUB_LOWER_CASE' => 'р',
];

/**
 * Функция возвращает отформатированную сумму вместе со знаком рубля
 * @param string|int $price цена - целое число
 * @param string $symbol знак валюты строчный или заглавный (для строкового представления цены в HTML)
 * @return string строка в виде числа с добавлением знака рубля
 */
function price_format(string|int $price, string $symbol = ''): string
{
    $price = (int)$price;
    $symbol = $symbol ? CURRENCY[$symbol] : '';
    return number_format($price, 0, ',', ' ') . ' ' . $symbol;
}
