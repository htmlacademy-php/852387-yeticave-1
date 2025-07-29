<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('init.php');
require_once('utilities/helpers.php');
require_once('utilities/date-time.php');
require_once('models/lots.php');
require_once('models/bets.php');
require_once('validate/validate-bet.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var array{id: int, date_end: string, lot_name: string, img_url: string,
 *      description: string, price_start: int, step_bet: int, cat_name: string} $lot
 * * все данные лота по ID лота из БД
 * @var ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 * @var string[] $form заполненные пользователем поля формы
 * @var ?string[] $errors массив ошибок по данным из формы
 * @var ?int $cost текущая цена лота
 * @var ?array $data массив с данными [ID лота и данные лота по ID из БД]
 * @var int $user_id_max_bet ID пользователя максимальной ставки по лоту
 * @var int $min_cost минимальная ставка по лоту
 *
 */

const RUB_LOWER_CASE = 'RUB_LOWER_CASE';
$form = [];
$errors = null;
$cost = null;
$user_id_max_bet = null;
$min_cost = null;

$title = 'Страница лота';

$data = check_id($_GET['id'], $connect);
if (!$data) {
    http_response_code(404);
    $path = '404.php';
} else {
    [$id, $lot] = $data;
    $bets = get_bets_by_lot_id($connect, $id);
    $cost = !empty($bets) ? find_max_bet($bets)['cost'] : $lot['price_start'];
    $min_cost = intval($cost) + intval($lot['step_bet']);
    $user_id_max_bet = get_id_user_by_last_bet_on_lot($connect, $id);
    $path = 'lot.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из полей формы
    $form = get_bet_fields();
    // получаем массив ошибок по данным полей из формы
    $errors = array_filter(get_errors($form, $min_cost));

    if (empty($errors)) {
        $is_set_bet = set_bet($connect, $_SESSION['user']['id'], $lot['id'], $form['cost']);
        $bets = get_bets_by_lot_id($connect, $id);
        $cost = !empty($bets) ? find_max_bet($bets)['cost'] : $lot['price_start'];
        $min_cost = intval($cost) + intval($lot['step_bet']);

        if (!$is_set_bet) {
            die(mysqli_error($connect));
        }
        header("Location: /lot.php?id=$lot[id]");
    }
}

$page_content = include_template($path, [
    'categories' => $categories,
    'lot' => $lot,
    'form' => $form,
    'bets' => $bets,
    'cost' => $cost,
    'user_id' => $user_id_max_bet,
    'min_cost' => $min_cost,
    'symbol' => RUB_LOWER_CASE,
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);