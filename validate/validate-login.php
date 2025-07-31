<?php
declare(strict_types=1);

require_once('utilities/validation.php');

// обязательные поля формы для заполнения
const REQUIRED = ['email', 'password'];

// ошибки при не заполненном поле формы
const EMPTY_FIELDS = [
    'email' => 'Введите e-mail',
    'password' => 'Введите пароль',
];

/** Получаем отфильтрованный массив полей формы заполненных пользователем
 * @return ?array{email: string, password: string} заполненные пользователем поля формы
 **/
function get_login_fields(): ?array
{
    $user_fields = filter_input_array(INPUT_POST, [
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => FILTER_SANITIZE_SPECIAL_CHARS,
    ]);
    $user_fields['email'] = $user_fields['email'] ?: '';
    return array_map(fn($value) => $value, $user_fields);
}

/**
 * Получаем строковое пояснение ошибки, если данные пользователя не найдены в БД
 * @param ?array $data данные зарегистрированного пользователя из БД
 * @return ?string
 **/
function validate_email(?array $data): ?string
{
    if (!$data) {
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