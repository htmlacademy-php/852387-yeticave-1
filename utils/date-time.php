<?php
declare(strict_types=1);

// Количество секунд в одном часе
const SECS_IN_HOUR = 3600;
// Количество секунд в одной минуте
const SECS_IN_MINUTE = 60;

const NOUN_PLURAL_FORM = [
    'hours' => ['час', 'часа', 'часов'],
    'minutes' => ['минута', 'минуты', 'минут'],
];

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
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    return $dateTimeObj !== false && (!date_get_last_errors() || array_sum(date_get_last_errors()) === 0);
}

/**
 * Возвращает интервал между переданной датой и сегодняшним днем в секундах
 * @param string $datetime дата в формате строки // 2025-05-30 // 2018-10-02 21:03:17
 * @return false|int может быть как положительное число или отрицательное число
 **/
function get_interval_in_second(string $datetime): false|int
{
    $ts = time();
    $end_ts = strtotime($datetime);
    return $end_ts - $ts;
}

/**
 * Функция возвращает остаток времени до данной даты в виде массива часов и минут и секунд
 * @param string $date_end дата истечения лота
 * @param bool $is_bet опция-флаг = передаваемая дата относиться к ставке TRUE, дата относится к лоту FALSE
 * @return array{hours: float|int, minutes: float|int} массив ['hours' => количество часов,
 *                        'minutes' => количество минут, 'second' => количество секунд]
 */
function get_dt_range(string $date_end, bool $is_bet = true): array
{
    $ts_diff = get_interval_in_second($date_end);
    $ts_diff = $is_bet ? abs($ts_diff) : $ts_diff;
    if ($ts_diff < 0) {
        return [
            'hours' => 0,
            'minutes' => 0,
            'seconds' => 0,
        ];
    }
        $hours = floor($ts_diff/ SECS_IN_HOUR);
        $minutes = floor(($ts_diff % SECS_IN_HOUR) / SECS_IN_MINUTE);
        $seconds = (int)floor($ts_diff % SECS_IN_MINUTE);
    return [
        'hours' => $hours,
        'minutes' => $minutes,
        'seconds' => $seconds,
    ];
}

/**
 * Возвращает разницу в днях
 *
 * @param string $date дата в формате «ГГГГ-ММ-ДД»
 * @return float
 **/
function diff_date(string $date): float
{
    return floor((time() - strtotime($date)) / SECS_IN_HOUR);
}

/**
 * Функция форматирует массив времени в строку
 *
 * @param array{hours: int, minutes: int, second: int} $time массив ['hours' => количество часов,
 *                                          'minutes' => количество минут, 'second' => количество секунд]
 * @return string время в виде строки НН:ММ
 */
function timer_format(array $time): string
{
    $result = [];
    foreach ($time as $value) {
        $result[] = str_pad((string)$value, 2, "0", STR_PAD_LEFT);
    }
    return implode(':', $result);
}

/**
 * Возвращает строковое представление таймера ставки
 * @param string $datetime дата ставки в формате строки // 2025-05-30 // 2018-10-02 21:03:17
 * @return string // 18 часов назад // 7 минут назад // 25.07.2025 в 02:55
 **/
function bet_time_format(string $datetime): string
{
    $timer = get_dt_range($datetime);
    $hours = $timer['hours'];
    $minutes = $timer['minutes'];
    $days = $hours / 24;

    if ($days < 1) {
        [$timer_units, $units] = $hours > 0 ? [$hours, 'hours'] : [$minutes, 'minutes'];
        $noun_plural_form_units = get_noun_plural_form((int)$timer_units, ...NOUN_PLURAL_FORM[$units]);
        return "$timer_units $noun_plural_form_units назад";
    }
    return date('d.m.y в H:i', strtotime($datetime));//"d.m.y в H:i");
}

/**
 * Возвращает TRUE если, срок по лоту завершился - 0 часов и 0 минут и 0 секунд
 * @param array $timer массив кол-ва часов, минут и секунд
 * @return bool
 **/
function is_expiration_date(array $timer): bool
{
    return $timer['hours'] === 0 and $timer['minutes'] === 0 and $timer['seconds'] === 0;
}
