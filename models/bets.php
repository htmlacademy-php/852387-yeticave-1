<?php
declare(strict_types=1);

require_once ('utils/db.php');
require_once ('data.php');

/**
 * Получает список всех ставок по ID лота или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $id ID лота, все ставки которого нужно найти в БД
 * @param int $limit Количество свежих ставок, которые можно получить в БД
 * @return ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string}
 **/

function get_bets_by_id(mysqli $connect, int $id, int $limit = LIMIT_ITEMS): ?array
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
