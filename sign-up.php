<?php
declare(strict_types=1);

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|object $connect mysqli Ресурс соединения
 * @var int $is_auth
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array $errors все ошибки заполнения формы пользователем
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * * все новые лота из БД
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var ?array $lot заполненные пользователем поля формы
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