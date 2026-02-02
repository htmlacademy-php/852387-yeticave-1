<?php
declare(strict_types=1);

require_once ('init.php');

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var int $is_auth рандомно число 1 или 0
 * @var ?array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var string $main_content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var ?array $form заполненные пользователем поля формы
 */


if (isset($_SESSION['user'])) {
    http_response_code(403);
    exit;
}

$title = 'Регистрация аккаунта';
$form = [];

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
    $main_content = include_template('sign-up.php', [
        'categories' => $categories,
    ]);
}

$layout_content = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);
