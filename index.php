<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once('data.php');
require_once ('init.php');

/**
 * @var boolean|object $connect
 * @var string $user_name
 * @var string[] $categories
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 */

if (!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
} else {
    print('Соединение установлено');
    // выполнение запросов
    $sql = '';
    $result = mysqli_query($connect, $sql);

    if (!$result) {
        $error = mysqli_error($connect);
        print("Ошибка MySQL: " . $error);
    }

    mysqli_set_charset($connect, "utf8");

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
