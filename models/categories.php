<?php
declare(strict_types=1);

require_once('utilities/helpers.php');

const LIMIT_CATEGORIES = 10;

/**
 * Получает список всех категорий или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество категорий, которые можно получить в БД
 * @return ?array<int,array{id: string, name: string, code: string}
**/
function get_categories(mysqli $connect, int $limit = LIMIT_CATEGORIES): ?array
{
    $sql = 'SELECT id, name, code FROM categories LIMIT ?';
    return get_items($connect, $sql, $limit);
}

