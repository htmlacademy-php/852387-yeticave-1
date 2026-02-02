<?php
declare(strict_types=1);

require_once ('utils/helpers.php');

/**
 * Получаем данные из БД в виде ассоциативного массива или завершаем код с ошибкой
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param mixed $data Данные для вставки на место плейсхолдеров
 *
 * @return ?array
 **/

function get_items(mysqli $link, string $sql, ...$data) : ?array
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!isset($result)) {
        die(mysqli_error($link));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}