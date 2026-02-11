<?php
declare(strict_types=1);

/**
 * Фильтрация ошибок
 *
 * @param ?array $data
 * @param array $rules
 * @param string[] $required обязательные поля формы для заполнения
 * @param string[] $empty_fields ошибки при не заполненном поле формы
 * @return array
 */

function filter_errors(?array $data, array $rules, array $required, array $empty_fields): array
{
    $errors = [];
    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $required, true) && (empty($value))) {
            $errors[$key] = $empty_fields[$key];
        }
    }
    // убираем все значения типа null, валидные значения
    return array_filter($errors);
}

/**
 * Возвращает строковое представление ошибки, если длина строки меньше минимально допустимого значения
 * и больше максимально допустимого значения
 * @param string $str данная строка
 * @param int $max максимальное значение длины строки
 * @param int $min минимальное значение длины строки
 * @return ?string
 **/
function validate_length(string $str, int $min, int $max) : ?string
{
    if (($str !== '' && strlen($str) < $min) || strlen($str) > $max) {
        return "Значение должно быть от $min до $max символов";
    }
    return null;
}
