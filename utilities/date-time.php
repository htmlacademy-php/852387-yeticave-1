<?php
declare(strict_types=1);

const SECS_IN_HOUR = 3600;
const SECS_IN_MINUTE = 60;

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && (!date_get_last_errors() || array_sum(date_get_last_errors()) === 0);
}

/**
 * Функция форматирует массив времени в строку
 * @param int[] $time массив [количество часов, количество минут]
 * @return string
 */
function time_format(array $time): string
{
    $hours_format = str_pad(strval($time['hours']), 2, "0", STR_PAD_LEFT);
    $minutes_format = str_pad(strval($time['minutes']), 2, "0", STR_PAD_LEFT);
    return "{$hours_format}:{$minutes_format}";
}

/**
 * Функция возвращает остаток времени до данной даты
 * @param string $date_end дата истечения лота
 * @return int[] [остаток часов до даты, остаток минут]
 */
function get_dt_range(string $date_end): array
{
    $ts = time();
    $end_ts = strtotime($date_end);
    $ts_diff = $end_ts - $ts;

    if ($ts_diff < 0) {
        return [
            'hours' => 0,
            'minutes' => 0
        ];
    }

    $hours_until_end = floor($ts_diff / SECS_IN_HOUR);
    $minutes_until_end = abs(floor($ts_diff % SECS_IN_HOUR / SECS_IN_MINUTE));

    return [
        'hours' => $hours_until_end,
        'minutes' => $minutes_until_end,
    ];
}

/**
 * Возвращает разницу в днях
 * @param string $date дата в формате «ГГГГ-ММ-ДД»
 * @return float
 *
**/
function diff_date(string $date) : float
{
    return floor((strtotime('now') - strtotime($date)) / SECS_IN_HOUR);
}
