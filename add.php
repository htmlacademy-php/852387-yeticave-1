<?php
declare(strict_types=1);

require_once ('data.php');
require_once ('init.php');
require_once ('utils/helpers.php');
require_once ('models/categories.php');

/**
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var string $title заголовок страницы сайта
 * @var string $user_name имя авторизованного пользователя
 * @var int $is_auth рандомно число 1 или 0
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var string $main_content HTML-код - контент страницы
 * @var string $page весь HTML-код страницы с подвалом и шапкой
 */

$title = 'Добавление лота';

if (!$connect) {
    die(mysqli_connect_error());
}
// выполнение запроса на список категорий
$categories = get_categories($connect);

$main_content = include_template('add.php', [
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

$page = include_template('layout.php', [
    'main_content' => $main_content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($page);
