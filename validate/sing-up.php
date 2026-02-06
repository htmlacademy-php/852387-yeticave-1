<?php
declare(strict_types=1);

require_once ('utils/validation.php');

// обязательные поля формы для заполнения
const REQUIRED = ['email', 'password', 'name', 'message'];

// ошибки при не заполненном поле формы
const EMPTY_FIELDS = [
    'email' => 'Введите e-mail',
    'password' => 'Введите пароль',
    'name' => 'Введите имя',
    'message' => 'Напишите как с вами связаться',
];

/** Получаем отфильтрованный массив полей формы заполненных пользователем
 * @return ?array
 **/
function get_registration_fields(): ?array
{
    $user_fields = filter_input_array(INPUT_POST, [
        'name' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => FILTER_SANITIZE_SPECIAL_CHARS,
        'message' => FILTER_SANITIZE_SPECIAL_CHARS,
    ]);
    $user_fields['email'] = $user_fields['email'] ?: '';
    return array_map(fn($value) => trim($value), $user_fields);
}

/**
 * Получаем строковое пояснение ошибки, если EMAIL найден среди списка EMAIL-ов пользователей из БД
 * @param ?string $email введенный EMAIL пользователя
 * @param array $emails список всех EMAIL-ов зарегистрированных пользователей из БД
 * @return ?string
 **/
function validate_email(?string $email, array $emails): ?string
{
    if (in_array($email, $emails)) {
        return 'Указанный email уже используется другим пользователем';
    }
    return null;
}

/**
 * Возвращает массив строковых значений ошибок по полученным данным
 * @param ?array $data ассоциативный массив с данными (полученных из формы на странице добавления нового лота)
 * @param array $emails список ID из БД по нужным элементам
 * @return array
 **/
function get_errors(?array $data, array $emails): array
{
    $rules = [
        'name' => function ($value) {
            return validate_length($value, 4, 30);
        },
        'email' => function ($value) use ($emails) {
            return validate_email($value, $emails);
        },
        'password' => function ($value) {
            return validate_length($value, 6, 12);
        },
        'message' => function ($value) {
            return validate_length($value, 8, 3000);
        },
    ];
    return filter_errors($data, $rules, REQUIRED, EMPTY_FIELDS);
}
