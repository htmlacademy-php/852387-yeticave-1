<?php
declare(strict_types=1);

/**
 * Функция возвращает отформатированную сумму вместе со знаком рубля
 *
 * @param int $price цена - целое число
 * @return string строка в виде числа с добавлением знака рубля
 */
function price_format(int $price): string
{
    return number_format($price, 0, ',', ' ') . ' ₽';
}
