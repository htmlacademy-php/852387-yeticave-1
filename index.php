<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once('data.php');

/**
 * @var string $user_name
 * @var string[] $categories
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 */

$con = mysqli_connect('localhost', 'root', '12345678', 'yeticave');
if ($con == false) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}
else {
    print('Соединение установлено');
    // выполнение запросов
    $sql = "INSERT INTO users SET email = 'developer@php.net', password = 'secret'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        $error = mysqli_error($con);
        print("Ошибка MySQL: " . $error);
    }

    mysqli_set_charset($con, "utf8");

    $page_content = include_template('main.php', [
        'categories' => $categories,
        'lots' => $lots,
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'title' => 'Главная',
        'is_auth' => rand(0, 1),
        'user_name' => $user_name,
        'categories' => $categories,
    ]);

    print($layout_content);
}
