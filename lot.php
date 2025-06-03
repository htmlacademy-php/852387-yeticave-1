<?php
declare(strict_types=1);

require_once('helpers.php');
require_once ('init.php');

const LIMIT_ITEMS = 10;

/**
 * @var string $user_name
 * @var boolean|object $connect
 * @var string $user_name
 * @var string[] $categories
 * @var array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lot
 */

$user_name = 'Татьяна';
$is_auth = rand(0, 1);
$categories = [];
$lot = [];
$page_content = '';

if (!$connect) {
    die(mysqli_connect_error());
}
// выполнение запроса на список категорий
$sql = 'SELECT *  FROM categories LIMIT ?';

$categories = getItems($connect, $sql, LIMIT_ITEMS);

$id = mysqli_real_escape_string($connect, $_GET['id']);

$sql = 'SELECT *, c.name "cat_name" FROM lots l
    INNER JOIN categories c ON l.cat_id = c.id
                            WHERE l.id = ?';

$lot = getItems($connect, $sql, $id);
//print(include_template('index.php', ['content' => $content, 'categories' => $categories]));

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'lots' => $lot,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
]);

print($layout_content);