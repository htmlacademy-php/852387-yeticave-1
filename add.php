<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('init.php');
require_once ('utils/helpers.php');
require_once ('models/categories.php');
require_once ('models/lots.php');
require_once ('validate.php');
require_once ('validate_upload_file.php');

/**
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var int $is_auth рандомно число 1 или 0
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * @var ?array $lot заполненные пользователем поля формы
 * @var string $main_content HTML-код - контент страницы
 * @var string $page весь HTML-код страницы с подвалом и шапкой
 */

$title = 'Добавление лота';
// получаем список ID всех категорий
$cat_ids = array_column($categories, 'id');
$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из полей формы
    $form = get_lot_fields();
    // получаем массив ошибок по данным полей из формы
    $errors = get_errors($form, $cat_ids);

    [$errors['file'], $form['img_url']] = validate_upload_file($_FILES['lot_img']);
    $errors = array_filter($errors);

    if (!$errors) {
        set_lot($connect, $form);
        $lot_id = mysqli_insert_id($connect);
        header('Location: /lot.php?id=' . $lot_id);
    }
}

$main_content = include_template('add.php', [
    'form' => $form,
    'errors' => $errors,
    'categories' => $categories
]);

$page = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($page);
