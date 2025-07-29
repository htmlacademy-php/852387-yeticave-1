<?php
declare(strict_types=1);

require_once('init.php');
require_once('models/users.php');
require_once('validate/validate-sign-up.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array $errors все ошибки заполнения формы пользователем
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 */

if ($_SESSION) {
    http_response_code(403);
    exit;
}

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
        $page_content = include_template('sign-up.php', [
            'form' => $form,
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        $is_set_user = set_user($connect, $form);

        if (!$is_set_user) {
            die(mysqli_error($connect));
        }

        header("Location: /login.php");
        exit();
    }
} else {
    $page_content = include_template('sign-up.php', [
        'categories' => $categories,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);