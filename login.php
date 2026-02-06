<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('models/users.php');
require_once ('validate/login.php');

/**
 * @var string $title заголовок страницы сайта
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array $form заполненные пользователем поля формы
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var string $content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
 */

//  $email = (int)filter_input(INPUT_POSt, 'email', FILTER_VALIDATE_EMAIL);
//  if ($email) { $user_in_bd = get_user_by_email(mysqli $connect, string $email); }

$title = 'Вход на сайт';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из полей формы
    $form = get_fields();
    // получаем данные пользователя (или null) по email
    $user = $form['email'] ? get_user_by_email($connect, $form['email']) : null;
    // получаем массив ошибок по данным полей из формы
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

$content = include_template('login.php', [
    'categories' => $categories,
    'form' => $form,
    'errors' => $errors,
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout);
