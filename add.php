<?php

declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once('init.php');
require_once('models/categories.php');
//require_once('models/lots.php');

/**
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var boolean|object $connect mysqli Ресурс соединения
 * @var int $is_auth
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string, price_start: string, img_url: ?string, date_end: string} $lots
 * * все новые лота из БД
 */

if (!$connect) {
    die(mysqli_connect_error());
}
$title = 'Добавление лота';
// выполнение запроса на список категорий
$categories = getCategories($connect);

$page_content = include_template('add.php', [
    'categories' => $categories,
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot = $_POST;
    $filename = $_FILES['lot_img']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = uniqid() . $extension;
    $lot['img_url'] = $filename;
    move_uploaded_file($_FILES['lot_img']['tmp_name'], 'uploads/' . $filename);

    $sql = 'INSERT INTO lots(user_id, name, description, img_url, price, date_end, step_bet, cat_id)
            VALUES (3, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($connect, $sql, $lot);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        $lot_id = mysqli_insert_id($connect);

        header('Location: add.php?id=' . $lot_id);
    } else {
        // выводим страницу 404.php? и пропадают введенные данные
        // выводим ошибку (отсутствия соединения с БД, отсутствие интернета, или другой форс-мажор)? и пропадают веденные данные
        // или возвращаем страницу с формой и с заполненными полями и просим загрузить ещё раз данные (сейчас или позже)?
        // смотрим код ошибки и выводим соответствующую страницу
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);
