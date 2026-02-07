<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('utils/db.php');

/**
 * Получает список всех категорий или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество категорий, которые можно получить в БД
 * @return ?array<int,array{id: int, name: string, code: string}
 **/
function get_categories(mysqli $connect, int $limit = LIMIT_ITEMS): ?array
{
    $sql = 'SELECT id, name, code FROM categories LIMIT ?';
    return get_items($connect, $sql, $limit);
}


/**
 * Возвращает наименований категории из БД
 * @param mysqli $connect Ресурс соединения
 * @param $id
 * @return ?string
 */
function get_category_name(mysqli $connect, $id): ?string
{
    $sql = 'SELECT name FROM categories WHERE id =' . $id;
    $result = mysqli_query($connect, $sql);
    return mysqli_fetch_assoc($result)['name'];
}


