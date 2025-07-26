<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('utilities/helpers.php');
require_once('init.php');
require_once('models/categories.php');
require_once ('models/lots.php');
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
 * @var ?array $lot заполненные пользователем поля формы
 */

if(!$_SESSION) {
    http_response_code(403);
    exit;
}

$title = 'Добавление лота';
// получаем список ID всех категорий
$cat_ids = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из полей формы
    $lot = get_lot_fields();
    // получаем массив ошибок по данным полей из формы
    $errors = get_errors($lot, $cat_ids);
    // проверка загрузки файла
    [$errors['file'], $lot['img_url']] = validate_upload_file($_FILES['lot_img']);
    // убираем все значения типа null, валидные значения
    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template('add.php', [
            'lot' => $lot,
            'errors' => $errors,
            'categories' => $categories
        ]);
    }
    else {
        $is_set_lot = set_lot($connect, $lot);

        if (!$is_set_lot) {
            die(mysqli_error($connect));
        }
        $lot_id = mysqli_insert_id($connect);
        header('Location: lot.php?id=' . $lot_id);
    }
}
else {
    $page_content = include_template('add.php', [
        'categories' => $categories,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);
