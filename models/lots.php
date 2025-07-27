<?php
declare(strict_types=1);

require_once('utilities/helpers.php');

const LIMIT_LOTS = 9;

/**
 * Получает список лотов или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество новых лотов, которые можно получить в БД
 * @param int $offset смещение
 * @return ?array<int,array{id: string, date_end: string, lot_name: string, price_start: string, img_url: string, last_bet: string, cat_name: string}
 **/
function get_lots(mysqli $connect, int $limit = LIMIT_LOTS, int $offset = 0): ?array
{
    $sql = 'SELECT l.id,
       l.date_end,
       l.name "lot_name",
       price "price_start",
       img_url,
       MAX(b.cost) "price_last",
       c.name "cat_name" FROM lots l
           LEFT JOIN bets b ON b.lot_id = l.id
           JOIN categories c ON l.cat_id = c.id
                         WHERE l.date_end > DATE(NOW())
                         GROUP BY l.id
                         ORDER BY l.date_add DESC LIMIT ? OFFSET ?';
    return get_items($connect, $sql, $limit, $offset);
}

/**
 * Получает данные лота по ID из таблицы БД
 * @param mysqli $connect Ресурс соединения
 * @param int $id ID лота
 * @return ?array{id: string, author_id: string, date_end: string, lot_name: string, img_url: string, description: string, price_start: string, last_bet: string, cat_name: string}
**/
function get_lot_by_id(mysqli $connect, int $id): ?array
{
    $sql = 'SELECT l.id,
        l.user_id "author_id",
        l.date_end,
        l.name "lot_name",
        l.img_url,
        l.description,
        l.price "price_start",
        l.step_bet,
        c.name "cat_name" FROM lots l
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
    var_dump($sql);
    var_dump($data);
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    return mysqli_stmt_execute($stmt);
}

function  search_lots(mysqli $connect, string $search, int $limit = LIMIT_LOTS, int $offset = 0): ?array
{
    $sql ='SELECT l.id,
        l.user_id "author_id",
        l.date_end,
        l.name "lot_name",
        l.img_url,
        l.description,
        l.price "price_start",
        l.step_bet,
        c.name "cat_name" FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
            WHERE MATCH(l.name, description) AGAINST(?)
            ORDER BY l.date_add DESC LIMIT ? OFFSET ?';
    return get_items($connect, $sql, $search, $limit, $offset);
}

function count_lots_by_search(mysqli $connect, string $search): int
{
    $sql = 'SELECT COUNT(*) as count FROM lots
            WHERE MATCH(name, description) AGAINST(?)';
    $result = get_item($connect, $sql, $search);
    return $result['count'];
}

function count_lots(mysqli $connect)
{
    $sql = 'SELECT COUNT(*) as count FROM lots';
    $result = mysqli_query($connect, $sql);
    return mysqli_fetch_assoc($result)['count'];
}

function count_lots_by_category(mysqli $connect, $cat_id)
{
    $sql = 'SELECT COUNT(*) as count FROM lots WHERE cat_id = ?';
    $result = get_item($connect, $sql, $cat_id);
    return $result['count'];
}

function get_lots_by_category(mysqli $connect, $cat_id, int $limit = LIMIT_LOTS, int $offset = 0): ?array
{
    $sql = 'SELECT l.id,
        l.user_id "author_id",
        l.date_end,
        l.name "lot_name",
        l.img_url,
        l.description,
        l.price "price_start",
        l.step_bet,
        c.name "cat_name" FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
                              WHERE l.cat_id = ?
                              ORDER BY l.date_add DESC LIMIT ? OFFSET ?';
    return get_items($connect, $sql, $cat_id, $limit, $offset);
}