<?php
declare(strict_types=1);

require_once('init.php');
require_once('models/users.php');
require_once('validate/validate-sign-up.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var ?array{name: string, email: string, password: string, message: string} $form заполненные пользователем поля формы
 * @var ?array{name: string, email: string, password: string, message: string} $errors массив ошибок по данным из формы
 */

if (isset($_SESSION['user'])) {
    http_response_code(403);
    exit;
}

$title = 'Регистрация аккаунта';
$form = [];
$users = get_users($connect);
$emails = array_column($users, 'email');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = get_registration_fields();
    $errors = get_errors($form, $emails);

    if (count($errors) === 0) {
        set_user($connect, $form);
        header("Location: /login.php");
        exit();
    }
}

$page_content = include_template('sign-up.php', [
    'categories' => $categories,
    'form' => $form,
    'errors' => $errors,
]);


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);