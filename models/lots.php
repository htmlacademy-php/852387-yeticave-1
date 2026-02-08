<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('utils/db.php');

/**
 * Получает список лотов или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество новых лотов, которые можно получить в БД
 * @param int $offset смещение
 * @return ?array<int,array{id: string, date_end: string, name: string, price_start: string, img_url: string, last_bet: string, cat_name: string}
 **/
function get_lots(mysqli $connect, int $limit = LIMIT_ITEMS, int $offset = 0): ?array
{
    $sql = 'SELECT l.id,
       l.date_end,
       l.name,
       price AS "price_start",
       img_url,
       MAX(b.cost) AS "price_last",
       c.name AS "cat_name" FROM lots l
           LEFT JOIN bets b ON b.lot_id = l.id
           JOIN categories c ON l.cat_id = c.id
                         WHERE l.date_end > DATE(NOW())
                         GROUP BY l.id, l.date_add
                         ORDER BY l.date_add DESC LIMIT ?  OFFSET ?';
    return get_items($connect, $sql, $limit, $offset);
}

/**
 * Получает данные лота по ID из таблицы БД
 * @param mysqli $connect Ресурс соединения
 * @param int $id ID лота
 * @return ?array{id: string, author_id: string, date_end: string, name: string, img_url: string, description: string, price_start: string, last_bet: string, cat_name: string}
 **/

function get_lot_by_id(mysqli $connect, int $id): ?array
{
    $sql = 'SELECT l.id,
        l.user_id AS "author_id",
        l.date_end,
        l.name,
        l.img_url,
        l.description,
        l.price AS "price_start",
        l.step_bet,
        c.name AS "cat_name" FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
                              WHERE l.id = ?';
    return get_item($connect, $sql, $id);
}

/**
 * Формирует и выполняет SQL-запрос на добавление нового лота
 * @param mysqli $connect Ресурс соединения
 * @param string[] $data данные для добавления лота в БД
 * @return boolean
 **/
function set_lot(mysqli $connect, array $data): bool
{
    $sql = 'INSERT INTO lots(user_id, name, description, price, date_end, step_bet, cat_id, img_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    return mysqli_stmt_execute($stmt);
}

/**
 * Получает список лотов по заданным словам в описании и названии лотов
 * @param mysqli $connect Ресурс соединения
 * @param string $search
 * @param int $limit
 * @param int $offset смещение
 * @return array|null
 */
function  search_lots(mysqli $connect, string $search, int $limit = LIMIT_ITEMS, int $offset = 0): ?array
{
    $sql ='SELECT l.id,
        l.user_id AS "author_id",
        l.date_end,
        l.name,
        l.img_url,
        l.description,
        l.price AS "price_start",
        l.step_bet,
        c.name AS "cat_name" FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
            WHERE MATCH(l.name, description) AGAINST(?)
            ORDER BY l.date_add DESC LIMIT ?  OFFSET ?';
    return get_items($connect, $sql, $search, $limit, $offset);
}

/**
 * @param mysqli $connect Ресурс соединения
 * @return int
 */
function count_lots(mysqli $connect): int
{
    $sql = 'SELECT COUNT(*) as count FROM lots';
    $result = mysqli_query($connect, $sql);
    return mysqli_fetch_assoc($result)['count'];
}

/**
 * Получает количество лотов по поисковому запросу
 * @param mysqli $connect Ресурс соединения
 * @param string $search
 * @return ?int
 */
function count_lots_by_search(mysqli $connect, string $search): ?int
{
    $sql = 'SELECT COUNT(*) AS count FROM lots
            WHERE MATCH(name, description) AGAINST(?)';
    $result = get_item($connect, $sql, $search);
    return $result['count'];
}

/**
 * Возвращает количество лотов в данной категории
 * @param mysqli $connect Ресурс соединения
 * @param int $cat_id
 * @return ?int
 */
function count_lots_by_category(mysqli $connect, int $cat_id): ?int
{
    $sql = 'SELECT COUNT(*) AS count FROM lots WHERE cat_id = ?';
    return get_item($connect, $sql, $cat_id)['count'];
}

/**
 * Возвращает все лоты по ID категории из БД
 * @param mysqli $connect Ресурс соединения
 * @param $cat_id
 * @param int $limit
 * @param int $offset смещение
 * @return array|null
 */
function get_lots_by_category(mysqli $connect, $cat_id, int $limit = LIMIT_ITEMS, int $offset = 0): ?array
{
    $sql = 'SELECT l.id,
        l.user_id AS "author_id",
        l.date_end,
        l.name,
        l.img_url,
        l.description,
        l.price AS "price_start",
        l.step_bet,
        c.name AS "cat_name" FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
                              WHERE l.cat_id = ?
                              ORDER BY l.date_add DESC LIMIT ? OFFSET ?';
    return get_items($connect, $sql, $cat_id, $limit, $offset);
}

/**
 * Возвращает список лотов без победителей, дата истечения которых меньше или равна текущей дате
 * @param mysqli $connect ресурс соединения
 * @return ?array<int,array{lot_id: int, lot_name: string, user_win_id: int}> массив лотов
 *
 **/
function get_lots_without_win_and_finishing(mysqli $connect): ?array
{
    $sql = 'SELECT id AS "lot_id", name AS "lot_name", user_win_id FROM lots
                        WHERE user_win_id IS NULL AND date_end <= DATE(NOW())';
    $result = mysqli_query($connect, $sql);
    if ($result && mysqli_num_rows($result)) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return null;
}

/**
 * Обновляет данные лота в БД
 * @param mysqli $connect ресурс соединения
 * @param array{win_id: int, lot_id: int} $data данные для внесения изменений в лоте в таблице БД
 * @return boolean true/false
 *
 **/
function update_lot(mysqli $connect, array $data): bool
{
    $sql = 'UPDATE lots SET user_win_id = ?  WHERE id = ?';
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    return mysqli_stmt_execute($stmt);
}
