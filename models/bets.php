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
    $sql = 'SELECT user_id "customer_id",
                    lot_id,
                    date_add,
                    cost FROM bets
         WHERE lot_id = ?
         ORDER BY bets.date_add
         LIMIT ?';
    return get_items($connect, $sql, $id, $limit);
}
