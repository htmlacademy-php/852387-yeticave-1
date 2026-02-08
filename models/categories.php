<?php
declare(strict_types=1);

//require_once ('data.php');
require_once ('utils/db.php');

const LIMIT_CATEGORIES = 10;

/**
 * Получает список всех категорий или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество категорий, которые можно получить в БД
 * @return ?array<int,array{id: int, name: string, code: string}
 **/
function get_categories(mysqli $connect, int $limit = LIMIT_CATEGORIES): ?array
{
    $sql = 'SELECT id, name, code FROM categories LIMIT ?';
    return get_items($connect, $sql, $limit);
}


/**
 * Возвращает по ID категории наименование категории из БД
 * @param mysqli $connect Ресурс соединения
 * @param int $id ID категории
 * @return ?string название категории
 */
function get_category_name(mysqli $connect, int $id): ?string
{
    $sql = 'SELECT name FROM categories WHERE id =' . $id;
    $query = mysqli_query($connect, $sql);
    $result = mysqli_fetch_assoc($query);
    return $result ? $result['name'] : null;
}


