<?php
declare(strict_types=1);

require_once('utils/helpers.php');

/**
 * Получаем данные из БД в виде ассоциативного массива или завершаем код с ошибкой
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param mixed $data Данные для вставки на место плейсхолдеров
 * @return ?array
 **/
function get_items(mysqli $link, string $sql, ...$data): ?array
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!isset($result)) {
        die(mysqli_error($link));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получаем данные из БД в виде ассоциативного массива или завершаем код с ошибкой
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param mixed $data Данные для вставки на место плейсхолдеров
 * @return ?array
 **/

function get_item(mysqli $link, string $sql, ...$data): ?array
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        return null;
    }
    return mysqli_fetch_assoc($result);
}

/**
 * Находит элемент(ассоциативный массив) с данными по максимальной ставе
 * @var array<int,array{customer_id: int, lot_id: int, date_add: string,
 *     cost: int, user_name: string} $bets все ставки по лоту
 * @return array{customer_id: int, lot_id: int, date_add: string, cost: int, user_name: string}
 */

function find_max_bet(array $bets): array
{
    return array_reduce($bets, static function ($acc, $bet) {
        return $acc['cost'] < $bet['cost'] ? $bet : $acc;
    }, $bets[0]);
}
