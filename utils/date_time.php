<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

// Количество секунд в одном часе
const SECS_IN_HOUR = 3600;
// Количество секунд в одной минуте
const SECS_IN_MINUTE = 60;

/**
 * Функция возвращает остаток времени до данной даты в виде массива
 *
 * @param string $date дата истечения лота
 * @return array{hours: float|int, minutes: float|int} массив ['hours' => количество часов,
 *                                                           'minutes' => количество минут]
 */
function time_diff(string $date) : array
{
    $ts = time();
    $ts_end_time = strtotime($date);
    $ts_diff = $ts_end_time - $ts;
    if ($ts_diff < 0) {

        $hours = 0;
        $minutes = 0;

    } else {

        $hours = floor($ts_diff/ SECS_IN_HOUR);
        $minutes = floor(($ts_diff % SECS_IN_HOUR) / SECS_IN_MINUTE);

    }
    return [
        'hours' => $hours,
        'minutes' => $minutes
    ];
}

/**
 * Функция форматирует массив времени в строку
 *
 * @param array{hours: float|int, minutes: float|int} $time массив ['hours' => количество часов,
 *                                                          'minutes' => количество минут]
 * @return string время в виде строки НН:ММ
 */
function time_format(array $time): string
{
    $hours_format = str_pad(strval($time['hours']), 2, "0", STR_PAD_LEFT);
    $minutes_format = str_pad(strval($time['minutes']), 2, "0", STR_PAD_LEFT);

    return "{$hours_format}:{$minutes_format}";
}
