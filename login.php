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
    if (!count($errors) and $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    }
    if (count($errors)) {
        $page_content = include_template('login.php', [
            'form' => $form,
            'errors' => $errors,
            'categories' => $categories,
        ]);
    } else {
        header("Location: /index.php");
        exit();
    }
} else {
    $page_content = include_template('login.php', [
        'categories' => $categories,
    ]);

    if (!empty($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);
