<?php
declare(strict_types=1);

require_once('init.php');
require_once('models/users.php');
require_once('validate/validate-login.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array $errors все ошибки заполнения формы пользователем
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var ?array{email: string, password: string} $form заполненные пользователем поля формы
 * @var ?array{email: string, password: string} $errors массив ошибок по данным из формы
 */

$title = 'Вход на сайт';
$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = get_login_fields();
    $user = $form['email'] ? get_user_by_email($connect, $form['email']) : null;
    $errors = get_errors($form, $user);

    if ($user and password_verify($form['password'], $user['password'])) {
        $_SESSION['user'] = $user;
    } else {
        $errors['password'] = 'Вы ввели неверный пароль';
    }
}

if (!empty($_SESSION['user']) and count($errors) === 0) {
    header("Location: /index.php");
    exit();
}

$page_content = include_template('login.php', [
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
