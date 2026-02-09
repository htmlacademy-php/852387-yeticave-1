<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('utils/db.php');

const LIMIT_BETS = 10;

/**
 * Получает список всех ставок по ID лота или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $id ID лота, все ставки которого нужно найти в БД
 * @param int $limit Количество свежих ставок, которые можно получить в БД
 * @return ?array<int,array{customer_id: int, lot_id: int, date_add: string, cost: int, user_name: string}
 **/

function get_bets_by_lot_id(mysqli $connect, int $id, int $limit = LIMIT_BETS): ?array
{
    $sql = 'SELECT b.user_id AS customer_id,
                    b.lot_id,
                    b.date_add,
                    b.cost,
                    u.name AS user_name FROM bets b
                    INNER JOIN users u ON u.id = b.user_id
         WHERE lot_id = ?
         ORDER BY b.date_add DESC LIMIT ?';

    return get_items($connect, $sql, $id, $limit);
}

/**
 * Формирует и выполняет SQL-запрос на добавление нового лота
 * @param mysqli $connect Ресурс соединения
 * @param mixed ...$data данные для добавления лота в БД
 * @return boolean true/false
 */
function set_bet(mysqli $connect, mixed ...$data): bool
{
    $sql = 'INSERT INTO bets(user_id, lot_id, cost)
                VALUES (?, ?, ?)';
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    if (!mysqli_stmt_execute($stmt)) {
        die(mysqli_error($connect));
    }
    return true;
}


/**
 * Возвращает ID пользователя максимальной ставки по лоту
 * @param mysqli $connect Ресурс соединения
 * @param int $lot_id ID лота
 * @return ?int ID пользователя
 */
function get_id_user_by_last_bet_on_lot(mysqli $connect, int $lot_id): ?int
{
    $sql = "SELECT user_id FROM bets WHERE cost = (SELECT max(cost) from bets where lot_id = ?) and lot_id = ?";
    $user = get_item($connect, $sql, $lot_id, $lot_id);
    return $user['user_id'] ?? null;
}

/**
 * Получает список всех ставок по ID зарегистрированного user или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $user_id ID пользователя, все ставки которого нужно найти в БД
 * @return ?array<int,array{lot_id: string, cost: string, date_add: string, date_end_lot: string,
 *      lot_name: string, img_url: string, user_win_id: string, cat_name: string, author_contact: string}
 **/
function get_bets_by_user_id(mysqli $connect, int $user_id): ?array
{
    $sql = 'SELECT b.lot_id, MAX(b.cost) AS cost, b.date_add AS date_add,
                l.date_end AS date_end_lot, l.name AS lot_name, l.img_url, l.user_win_id,
                c.name AS cat_name,
                u.contact AS author_contact FROM bets b
                                            INNER JOIN lots l ON b.lot_id = l.id
                                            INNER JOIN categories c ON l.cat_id = c.id
                                            INNER JOIN users u ON l.user_id = u.id
                                  WHERE b.user_id = ?
                                  GROUP BY b.lot_id , b.date_add
                                  ORDER BY b.date_add DESC';

    return get_items($connect, $sql, $user_id);
}

/**
 * Возвращает список последних ставок по ID переданных лотов
 * @param mysqli $connect ресурс соединения
 * @param int[] $lots_ids список ID лотов в виде массива
 * @return ?array<int,array{user_id: int, lot_id: int, date_add: string, cost: int} массив ставок
 *
 **/
function get_last_bets_by_lots(mysqli $connect, array $lots_ids): ?array
{
    $insert = str_repeat('?, ', count($lots_ids) - 1) . '?';
    $sql = "SELECT b1.user_id, b1.lot_id, b1.date_add, b1.cost FROM bets b1
                    JOIN (SELECT lot_id, MAX(cost) AS cost FROM bets
                                                           GROUP BY lot_id) b2
                        ON (b1.lot_id = b2.lot_id AND b1.cost = b2.cost)
                                                   WHERE b2.lot_id IN ($insert)";
    return get_items($connect, $sql, ...$lots_ids);
}
