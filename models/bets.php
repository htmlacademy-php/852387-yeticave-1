<?php
declare(strict_types=1);

require_once ('utils/db.php');
require_once ('data.php');

const LIMIT_BETS = 20;

/**
 * Получает список всех ставок по ID лота или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $id ID лота, все ставки которого нужно найти в БД
 * @param int $limit Количество свежих ставок, которые можно получить в БД
 * @return ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string}
 **/

function get_bets_by_lot_id(mysqli $connect, int $id, int $limit = LIMIT_BETS): ?array
{
    $sql = 'SELECT b.user_id "customer_id",
                    b.lot_id,
                    b.date_add,
                    b.cost,
                    u.name "user_name" FROM bets b
                    INNER JOIN users u ON u.id = b.user_id
         WHERE lot_id = ?
         ORDER BY b.date_add DESC LIMIT ?';

    return get_items($connect, $sql, $id, $limit);
}

/**
 * Формирует и выполняет SQL-запрос на добавление нового лота
 * @param mysqli $connect Ресурс соединения
 * @param mixed ...$data данные для добавления лота в БД
 * @return boolean
 */
function set_bet(mysqli $connect, mixed ...$data): bool
{
    $sql = 'INSERT INTO bets(user_id, lot_id, cost)
                VALUES (?, ?, ?)';
    $stmt = db_get_prepare_stmt($connect, $sql, $data);

    return mysqli_stmt_execute($stmt);
}

function get_id_user_by_last_bet_on_lot(mysqli $connect, int $lot_id): ?array
{
    $sql = "SELECT user_id FROM bets WHERE cost = (SELECT max(cost) from bets where lot_id = ?)";

    return get_item($connect, $sql, $lot_id);
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
    $sql = 'SELECT b.lot_id, MAX(b.cost) AS "cost", b.date_add "date_add",
                l.date_end "date_end_lot", l.name "lot_name", l.img_url, l.user_win_id,
                c.name "cat_name",
                u.contact "author_contact" FROM bets b
                                            INNER JOIN lots l on b.lot_id = l.id
                                            INNER JOIN categories c on l.cat_id = c.id
                                            INNER JOIN users u ON l.user_id = u.id
                                  WHERE b.user_id = ?
                                  GROUP BY b.lot_id , b.date_add
                                  ORDER BY b.date_add DESC';

    return get_items($connect, $sql, $user_id);
}
