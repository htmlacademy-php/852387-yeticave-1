<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('helpers.php');
require_once ('init.php');

/**
 * @var string $user_name
 * @var boolean|object $connect
 * @var string $user_name
 * @var string[] $categories
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 */

$user_name = 'Татьяна';
$is_auth = rand(0, 1);
$categories = [];
$lots = [];
$page_content = '';

if (!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
} else {
    // выполнение запроса на список категорий
    $sql = 'SELECT *  FROM categories';
    $result = mysqli_query($connect, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print("Ошибка MySQL: " . $error);
    }
    // выполнение запроса на список новых лотов
    $sql = 'SELECT l.date_end,
                    l.name "lot_name",
                    price "price_start",
                    img_url,
                    GREATEST(price, IFNULL(b.cost, 0)) "cost",
                    c.name "cat_name"
            FROM lots l
                LEFT JOIN bets b ON b.lot_id = l.id
                INNER JOIN categories c ON l.cat_id = c.id
            WHERE l.date_end > DATE(NOW())
            ORDER BY l.date_add DESC;';

    $result = mysqli_query($connect, $sql);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print("Ошибка MySQL: " . $error);
    }
    $page_content = include_template('main.php', [
        'categories' => $categories,
        'lots' => $lots,
    ]);
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'title' => 'Главная',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'categories' => $categories,
    ]);
}

print($layout_content);
