<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('init.php');
require_once('utilities/helpers.php');
require_once('models/lots.php');
require_once('validate/validate-lot.php');
require_once('validate/validate-upload-file.php');

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var array $errors все ошибки заполнения формы пользователем
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * * все новые лота из БД
 * @var string $page_content содержимое шаблона страницы, в который передаем нужные ему данные
 * @var ?array{user_id: int, name: string, dessription: string, price: string, date_end: string, step_ben: string, cat_id: int} $form заполненные пользователем поля формы
 * @var ?array{user_id: int, name: string, dessription: string, price: string, date_end: string, step_ben: string, cat_id: int} $errors массив ошибок по данным из формы
 */

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit;
}

$title = 'Добавление лота';
$cat_ids = array_column($categories, 'id');
$form = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = get_lot_fields();
    $errors = get_errors($form, $cat_ids);
    [$errors['file'], $form['img_url']] = validate_upload_file($_FILES['lot_img']);
    $form['user_id'] = $_SESSION['user']['id'] ?? null;
    $errors = array_filter($errors);

    if (count($errors) === 0) {
        set_lot($connect, $form);
        $lot_id = mysqli_insert_id($connect);
        header('Location: lot.php?id=' . $lot_id);
    }
}

$page_content = include_template('add.php', [
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
