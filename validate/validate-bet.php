<?php
declare(strict_types=1);

require_once('utilities/validation.php');

// обязательные поля формы для заполнения
const REQUIRED = ['cost'];
// ошибки при не заполненном поле формы
const EMPTY_FIELDS = [
    'cost' => 'Введите вашу ставку',
];

/**
 * Получаем отфильтрованный массив полей формы заполненных пользователем
 * @return ?array
 **/
function get_bet_fields(): ?array
{
    return filter_input_array(INPUT_POST, [
        'cost' => FILTER_SANITIZE_NUMBER_INT,
    ]);
}

/**
 * Получаем строковое пояснение ошибки, если значение ставки не введено или меньше минимальной ставки
 * @param string|int $value значение введённое пользователем
 * @param int $min_cost минимальное значение ставки
 * @return string|null
 */
function validate_cost(string|int $value, int $min_cost): ?string
{
    if (intval($value) <= $min_cost) {
        return 'Ваша ставка меньше ' . $min_cost;
    }
    return null;
}

/**
 * Возвращает массив строковых значений ошибок по полученным данным
 * @param ?array $data ассоциативный массив с данными (полученных из формы на странице добавления нового лота)
 * @param ?int $data_bd данные пользователя из БД по указанному email
 * @return array
 **/
function get_errors(?array $data, ?int $data_bd): array
{
    $rules = [
        'cost' => function ($value) use ($data_bd) {
            return validate_cost((int)$value, $data_bd);
        },
    ];

    return array_filter(filter_errors($data, $rules, REQUIRED, EMPTY_FIELDS));
}