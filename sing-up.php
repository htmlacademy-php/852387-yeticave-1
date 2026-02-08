<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('models/users.php');
require_once ('validate/sing-up.php');

/**
 * @var string $title заголовок страницы сайта
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var ?string $cat_name название категории
 * @var ?array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?array{name: string, email: string, password: string, message: string} $errors все ошибки заполнения формы пользователем
 * @var string $content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var ?array{name: string, email: string, password: string, message: string} $form заполненные пользователем поля формы
 * @var ?array<int,array{id: int, date_add: string, name: string, email: string, password: string, contact: string} $users массив с параметрами по всем users из БД
 * @var string[] $emails список всех emails зарегистрированных пользователей
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
 */

if (isset($_SESSION['user'])) {
    http_response_code(403);
    exit;
}

$title = 'Регистрация аккаунта';
$users = get_users($connect);
$emails = array_column($users, 'email');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = get_registration_fields();
    $errors = get_errors($form, $emails);

    if (!$errors) {
        set_user($connect, $form);
        header("Location: /login.php");
        exit();
    }
}

$content = include_template('sing-up.php', [
    'form' => $form,
    'errors' => $errors,
    'categories' => $categories,
    'cat_name' => $cat_name,
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
    'cat_name' => $cat_name,
]);

print($layout);
