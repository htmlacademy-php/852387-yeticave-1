<?php
declare(strict_types=1);

require_once ('utils/validation.php');

// обязательные поля формы для заполнения
const REQUIRED = ['email', 'password'];

// ошибки при не заполненном поле формы
const EMPTY_FIELDS = [
    'email' => 'Введите e-mail',
    'password' => 'Введите пароль',
];

/** Получаем отфильтрованный массив полей формы заполненных пользователем
 * @return ?array
 **/
function get_fields(): ?array
{
    $user_fields = filter_input_array(INPUT_POST, [
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => FILTER_SANITIZE_SPECIAL_CHARS,
    ]);
    $user_fields['email'] = $user_fields['email'] ?: '';
    return array_map(fn($value) => $value, $user_fields);
}

/**
 *  Получаем строковое пояснение ошибки, если EMAIL не найден среди списка EMAIL-ов пользователей из БД
 * @param string $email введенный EMAIL пользователя
 * @return bool
 */
function has_email(string $email): bool
{

}
/**
 * Получаем строковое пояснение ошибки, если EMAIL найден среди списка EMAIL-ов пользователей из БД
 * @param ?array $data_bd список всех EMAIL-ов зарегистрированных пользователей из БД
 * @return ?string
 **/
function validate_email( ?array $data_bd) : ?string
{
    if(!$data_bd) {
        return 'Такой пользователь не найден';
    }
    return null;
}

/**
 * Возвращает массив строковых значений ошибок по полученным данным
 * @param ?array $data ассоциативный массив с данными (полученных из формы на странице добавления нового лота)
 * @param ?array $data_bd данные пользователя из БД по указанному email
 * @return array
 **/
function get_errors(?array $data, ?array $data_bd): array
{
    $rules = [
        'email' => function () use ($data_bd) {
            return validate_email($data_bd);
        },
        'password' => function ($value) {
            return validate_length($value, 6, 12);
        },
    ];
    return array_filter(filter_errors($data, $rules, REQUIRED, EMPTY_FIELDS));
}
