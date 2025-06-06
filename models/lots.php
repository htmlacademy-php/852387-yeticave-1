<?php
declare(strict_types=1);

require_once('helpers.php');

const LIMIT_LOTS = 10;

/**
 * Получает список лотов или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество новых лотов, которые можно получить в БД
 * @return ?array<int,array{id: string, date_end: string, lot_name: string, price_start: string, img_url: string, last_bet: string, cat_name: string}
 **/
function getLots(mysqli $connect, int $limit = LIMIT_LOTS): ?array
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
                         ORDER BY l.date_add DESC LIMIT ?';
    return get_items($connect, $sql, $limit);
}

function getLotById(mysqli $connect, int $id): ?array
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