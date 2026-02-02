<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('utils/db.php');

/**
 * Получает список лотов или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество новых лотов, которые можно получить в БД
 * @return ?array<int,array{id: string, date_end: string, lot_name: string, price_start: string, img_url: string, last_bet: string, cat_name: string}
 **/
function get_lots(mysqli $connect, int $limit = LIMIT_ITEMS): ?array
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
                         GROUP BY l.id, l.date_add
                         ORDER BY l.date_add DESC LIMIT ?';
    return get_items($connect, $sql, $limit);
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
        l.name,
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
                VALUES (3, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    return mysqli_stmt_execute($stmt);
}
