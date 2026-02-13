<?php
declare(strict_types=1);

require_once('init.php');
require_once('data.php');
require_once('utils/helpers.php');
require_once('utils/db.php');
require_once('models/lots.php');
require_once('models/bets.php');
require_once('utils/date-time.php');
require_once('utils/price.php');
require_once('validate/bet.php');

/**
 * @var string $title заголовок страницы сайта
 * @var bool|mysqli|object $connect ресурс соединения с сервером БД
 * @var array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var array{id: int, author_id: int, date_end: string, name: string, description: string,
 *     img_url: string, price_start: int, step_bet: int, cat_name: string} $lot все данные по ID лота из БД
 * @var ?array<int,array{customer_id: int, lot_id: int,
 *     date_add: string, cost: int, user_name: string} $bets все ставки по ID лота из БД
 * @var ?array{cost: int} $form заполненные пользователем поля формы
 * @var ?array{cost: string} $errors массив ошибок по данным из формы
 * @var ?int $cost текущая цена лота
 * @var ?int $user_id_max_bet ID пользователя максимальной ставки по лоту
 * @var ?int $min_cost минимальная ставка по лоту
 * @var ?string $cat_name название категории
 * @var string $content HTML-код - контент страницы
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
 * @var bool $is_logged пользователь авторизован на сайте
 * @var bool $is_author пользователь является автором лота
 * @var bool $is_user_max_bet пользователь является автором последней ставки по лоту
 */

const RUB_LOWER_CASE = 'RUB_LOWER_CASE';

$title = 'Страница лота';
$path = 'lot.php';

if (isset($_SESSION['user'])) {
    $is_logged = true;
    $user_id = $_SESSION['user']['id'] ?? null;
}

$lot_id = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$lot = get_lot_by_id($connect, $lot_id);

if (!$lot) {
    http_response_code(404);
    $path = '404.php';
} else {
    $bets = get_bets_by_lot_id($connect, $lot_id);
    $cost = !empty($bets) ? find_max_bet($bets)['cost'] : $lot['price_start'];
    $min_cost = $cost + $lot['step_bet'];
    $is_user_max_bet = is_identity(get_id_user_by_last_bet_on_lot($connect, $lot_id), $user_id ?? null);
    $is_author = is_identity($lot['author_id'], $user_id ?? null);
    $title = $lot['name'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = get_bet_fields();
    $errors = array_filter(get_errors($form, $min_cost));

    if (empty($errors)) {
        set_bet($connect, $_SESSION['user']['id'], $lot['id'], $form['cost']);
        $bets = get_bets_by_lot_id($connect, $lot_id);
        $cost = !empty($bets) ? find_max_bet($bets)['cost'] : $lot['price_start'];
        $min_cost = $cost + $lot['step_bet'];
        header('Location: /lot.php?id=' . $lot_id);
    }
}


$content = include_template($path, [
    'categories' => $categories,
    'lot' => $lot,
    'bets' => $bets,
    'form' => $form,
    'cost' => $cost,
    'user_id_max_bet' => $user_id_max_bet ?? null,
    'min_cost' => $min_cost ?? null,
    'symbol' => RUB_LOWER_CASE,
    'errors' => $errors ?? null,
    'is_logged' => $is_logged ?? false,
    'is_user_max_bet' => $is_user_max_bet ?? false,
    'is_author' => $is_author ?? false,
    'cat_name' => $cat_name,
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
    'cat_name' => $cat_name,
]);

print($layout);
