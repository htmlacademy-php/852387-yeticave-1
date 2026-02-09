<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('utils/helpers.php');
require_once ('models/lots.php');
require_once ('validate/add-lot.php');
require_once ('validate/upload-file.php');

/**
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var string $title заголовок страницы сайта
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории лотов из БД
 * @var ?array $form заполненные пользователем поля формы
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * @var ?array $lot заполненные пользователем поля формы
 * @var ?string $cat_name название категории
 * @var string $content HTML-код - контент страницы
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
 */

if (!$_SESSION) {
    http_response_code(403);
    exit;
}

$title = 'Добавление лота';
$cat_ids = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot['user_id'] = (int)$_SESSION['user']['id'];
    $form = [...$lot, ...get_lot_fields()];
    $errors = get_errors($form, $cat_ids);

    [$errors['file'], $form['img_url']] = validate_upload_file($_FILES['lot_img']);
    $errors = array_filter($errors);

    if (!$errors) {
        set_lot($connect, $form);
        $lot_id = mysqli_insert_id($connect);
        header('Location: /lot.php?id=' . $lot_id);
    }
}

$content = include_template('add-lot.php', [
    'form' => $form,
    'errors' => $errors,
    'categories' => $categories,
    'cat_name' => $cat_name
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
    'cat_name' => $cat_name
]);

print($layout);
