<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('init.php');
require_once ('models/users.php');
require_once ('validate/sing-up.php');

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var int $is_auth рандомно число 1 или 0
 * @var ?array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var string $content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var ?array $form заполненные пользователем поля формы
 * @var ?array<int,array{id: int, date_add: string, name: string, email: string, password: string, contact: string} $users массив с параметрами по всем users из БД
 * @var string[] $emails список всех emails зарегистрированных пользователей
 */

$title = 'Регистрация аккаунта';
// выполнение запроса на список всех пользователей
$users = get_users($connect);
// получаем список EMAIL всех пользователей
$emails = array_column($users, 'email');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из полей формы
    $form = get_registration_fields();
    // получаем массив ошибок по данным полей из формы
    $errors = get_errors($form, $emails);

    if (count($errors)) {
        $content = include_template('sing-up.php', [
            'form' => $form,
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        set_user($connect, $form);
        header("Location: /login.php");
        exit();
    }
} else {
    $content = include_template('sing-up.php', [
        'categories' => $categories,
    ]);
}

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout);
