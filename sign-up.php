<?php
declare(strict_types=1);

require_once('init.php');
require_once('models/categories.php');
require_once('models/users.php');
require_once('validate/validate-sign-up.php');

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|object $connect mysqli Ресурс соединения
 * @var int $is_auth
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array $errors все ошибки заполнения формы пользователем
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 */


if (!$connect) {
    die(mysqli_connect_error());
}
$title = 'Регистрация аккаунта';
// выполнение запроса на список категорий
$categories = get_categories($connect);
// выполнение запроса на список всех пользователей
$users = get_users($connect);
// получаем список EMAIL всех пользователей
$emails = array_column($users, 'email');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из полей формы
    $user = get_registration_fields();
    // получаем массив ошибок по данным полей из формы
    $errors = get_errors($user, $emails);

    if (count($errors)) {
        $page_content = include_template('sign-up.php', [
            'user' => $user,
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        $is_set_user = set_user($connect, $user);

        if (!$is_set_user) {
            die(mysqli_error($connect));
        }

        $page_content = include_template('login.php', [
            'categories' => $categories
        ]);
    }
}
else {
    $page_content = include_template('sign-up.php', [
        'categories' => $categories,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);