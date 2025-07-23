<?php
declare(strict_types=1);

require_once('init.php');
require_once('models/categories.php');

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из полей формы
    // получаем массив ошибок по данным полей из формы
    if (count($errors)) {
        $page_content = include_template('sign-up.php', [
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        $page_content = include_template('login.php', [
            'categories' => $categories
        ]);
    }
} else {
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