<?php
declare(strict_types=1);

const LIMIT_LOTS = 9;

/**
 * Получает список лотов или завершаем код с ошибкой
 * @param mysqli $connect Ресурс соединения
 * @param int $limit Количество новых лотов, которые можно получить в БД
 * @param int $offset смещение
 * @return ?array<int,array{id: int, date_end: string, name: string, price_start: int,
 *                              img_url: string, cost: int, cat_name: string}
 **/
function get_lots(mysqli $connect, int $limit = LIMIT_LOTS, int $offset = 0): ?array
{
    $sql = 'SELECT l.id,
       l.date_end,
       l.name,
       price AS price_start,
       img_url,
       MAX(b.cost) AS cost,
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
 * @return ?array{id: int, author_id: int, date_end: string, name: string, img_url: string,
 *              description: string, price_start: int, step_bet: int, cat_name: string}
 **/
function get_lot_by_id(mysqli $connect, int $id): ?array
{
    $sql = 'SELECT l.id,
        l.user_id AS author_id,
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
 * @return ?bool
 **/
function set_lot(mysqli $connect, array $data): ?bool
{
    $sql = 'INSERT INTO lots(user_id, name, description, price, date_end, step_bet, cat_id, img_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    if (!mysqli_stmt_execute($stmt)) {
        die(mysqli_error($connect));
    }
    return true;
}

/**
 * Получает список лотов по заданным словам в описании и названии лотов
 * @param mysqli $connect Ресурс соединения
 * @param string $search строка которую необходимо найти
 * @param int $limit лимит на количество получения лотов
 * @param int $offset смещение
 * @return ?array<int,array{id: int, author_id: int, date_end: string, name: string,
 *  img_url: string, description: string, price_start: int, step_bet: int,
 *  cat_name: string, cost: int}
 */
function search_lots(mysqli $connect, string $search, int $limit = LIMIT_ITEMS, int $offset = 0) : ?array
{
    $sql ='SELECT l.id,
        l.user_id AS author_id,
        l.date_end,
        l.name,
        l.img_url,
        l.description,
        l.price AS price_start,
        l.step_bet,
        c.name AS cat_name,
        MAX(b.cost) AS cost FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
            LEFT JOIN bets b ON b.lot_id = l.id
                              WHERE MATCH(l.name, description) AGAINST(?)
                              group by l.id, l.date_end, l.name, l.date_add
                              ORDER BY l.date_add DESC LIMIT ? OFFSET ?';
    return get_items($connect, $sql, $search, $limit, $offset);
}

/**
 * Получает количество лотов по поисковому запросу
 * @param mysqli $connect Ресурс соединения
 * @param string $search значение поиска
 * @return ?int количество лотов
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
 * @param int $cat_id ID категории
 * @return ?int количество лотов
 */
function count_lots_by_category(mysqli $connect, int $cat_id): ?int
{
    $sql = 'SELECT COUNT(*) AS count FROM lots WHERE cat_id = ?';
    $result = get_item($connect, $sql, $cat_id);
    return  $result['count'];
}

/**
 * Возвращает все лоты по ID категории из БД
 * @param mysqli $connect Ресурс соединения
 * @param int $cat_id ID категории
 * @param int $limit лимит на количества получаемых лотов из БД
 * @param int $offset смещение (с какого по счету лота нужна выборка)
 * @return ?array<int,array{id: int, author_id: int, date_end: string, name: string,
 *     img_url: string, decription: string, price_start: int, step_bet: int, cat_name: string,
 *     cost: int} массив данных лотов из БД
 */
function get_lots_by_category(mysqli $connect, int $cat_id, int $limit = LIMIT_LOTS, int $offset = 0): ?array
{
    $sql = 'SELECT l.id,
        l.user_id AS author_id,
        l.date_end,
        l.name,
        l.img_url,
        l.description,
        l.price AS price_start,
        l.step_bet,
        c.name AS cat_name,
        MAX(b.cost) AS cost FROM lots l
            INNER JOIN categories c ON l.cat_id = c.id
            LEFT JOIN bets b ON l.id = b.lot_id
                              WHERE l.cat_id = ?
                              group by l.id, l.date_end, l.name, l.date_add
                              ORDER BY l.date_add DESC LIMIT ? OFFSET ?';
    return get_items($connect, $sql, $cat_id, $limit, $offset);
}

/**
 * Возвращает список лотов без победителей, дата истечения которых меньше или равна текущей дате
 * @param mysqli $connect ресурс соединения
 * @return ?array<int,array{id: int, name: string, user_win_id: int}> массив лотов
 *
 **/
function get_lots_without_win_and_finishing(mysqli $connect): ?array
{
    $sql = 'SELECT id, name, user_win_id FROM lots
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
 * @param int[] $data данные для внесения изменений в лоте в таблице БД
 * @return bool true/false
 *
 **/
function update_lot(mysqli $connect, array $data): bool
{
    $sql = 'UPDATE lots SET user_win_id = ?  WHERE id = ?';
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    return mysqli_stmt_execute($stmt);
}
