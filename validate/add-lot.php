<?php
declare(strict_types=1);

require_once ('utils/date-time.php');
require_once ('utils/validation.php');

const REQUIRED = ['name', 'description', 'price', 'date_end', 'step_bet', 'cat_id'];

const EMPTY_FIELDS = [
    'name' => 'Введите наименование лота',
    'description' => 'Напишите описание лота',
    'price' => 'Введите начальную цену',
    'date_end' => 'Введите дату завершения торгов',
    'step_bet' => 'Введите шаг ставки',
    'cat_id' => 'Выберите категорию',
];

/** Получаем отфильтрованный массив полей формы заполненных пользователем
 * @return ?array
 **/
function get_lot_fields(): ?array
{
    $lot_fields = filter_input_array(INPUT_POST, [
        'name' =>  FILTER_SANITIZE_SPECIAL_CHARS,
        'description' => FILTER_SANITIZE_SPECIAL_CHARS,
        'price' => FILTER_SANITIZE_NUMBER_INT,
        'date_end' => FILTER_SANITIZE_SPECIAL_CHARS,
        'step_bet' => FILTER_SANITIZE_NUMBER_INT,
        'cat_id' => FILTER_SANITIZE_NUMBER_INT,
    ]);
    return array_map(static fn($value) => trim($value), $lot_fields);
}

/**
 * Получаем строковое пояснение ошибки при валидации даты или NULL, если ошибки нет
 * @param string $date полученная дата
 * @return ?string
 **/
function validate_date(string $date): ?string
{
    return match (true) {
        !is_date_valid($date) => 'Не верный формат даты. «Дата завершения торгов» должна быть датой в формате «ГГГГ-ММ-ДД»',
        diff_date($date) > 0 => '«Дата завершения торгов» должна быть больше текущей даты, хотя бы на один день',
        default => null
        };
}

/**
 * Проверка, что входящие данные целое число и больше 0 и получаем строковое пояснение ошибки, если есть
 * @var mixed $value
 * @return ?string
 **/
function validate_price(mixed $value): ?string
{
    return match (true) {
        is_int($value) => 'Введите целое число',
        $value <= 0 => 'Значение должно быть больше 0',
        default => null
        };
}

/**
 * Получаем строковое пояснение ошибки, если ID элемента не найдено среди списка IDs из БД
 * @param ?string $id данный ID элемента
 * @param array $ids список всех ID по элементам из БД
 * @return ?string
 **/
function validate_id(?string $id, array $ids): ?string
{
    return match (true) {
        !in_array($id, $ids, true) => 'Указана не существующая категория',
        default => null
        };
}

/**
 * Возвращает массив строковых значений ошибок по полученным данным
 * @param ?array $data ассоциативный массив с данными (полученных из формы на странице добавления нового лота)
 * @param array $ids список ID из БД по нужным элементам
 * @return array
 **/
function get_errors(?array $data, array $ids): array
{
    $rules = [
        'name' => function ($value) {
            return validate_length($value, 10, 200);
        },
        'description' => function ($value) {
            return validate_length($value, 10, 3000);
        },
        'price' => function ($value) {
            return validate_price($value);
        },
        'date_end' => function ($value) {
            return validate_date($value);
        },
        'step_bet' => function ($value) {
            return validate_price($value);
        },
        'cat_id' => function ($value) use ($ids) {
            return validate_id($value, $ids);
        },
    ];
    return filter_errors($data, $rules, REQUIRED, EMPTY_FIELDS);
}
