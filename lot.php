<?php
declare(strict_types=1);

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, 'ru_RU');

require_once('utilities/helpers.php');
require_once ('utilities/date-time.php');
require_once ('init.php');
require_once ('models/categories.php');
require_once ('models/lots.php');
require_once ('models/bets.php');
require_once ('validate/validate-bet.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array{id: string, author_id: string, date_end: string, lot_name: string, cat_name: string, price_start: string, img_url: string, description: string, step_bet: string} $lot
 * * все данные по ID лота из БД
 * @var ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 */

const RUB_LOWER_CASE = 'RUB_LOWER_CASE';

var_dump($_SESSION);

$title = 'Страница лота';

if (!isset($_GET['id'])) {
    http_response_code(404);
    $path = '404.php';
} else {
    $id = (int)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if (!$id) {
        http_response_code(404);
        $path = '404.php';
    } else {
        $lot = get_lot_by_id($connect, $id);
        if (!$lot) {
            http_response_code(404);
            $path = '404.php';
        } else {

            $bets = get_bets_by_lot_id($connect, $id);
            $cost = !empty($bets) ? find_max_bet($bets)['cost'] : $lot['price_start'];
            $min_cost = intval($cost) + intval($lot['step_bet']);
            $user_id_max_bet = get_id_user_by_last_bet_on_lot($connect, $id)['user_id'] ?? 0;
            var_dump($user_id_max_bet);
            $path = 'lot.php';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // получаем данные из полей формы
                $form = get_fields();
                // получаем массив ошибок по данным полей из формы
                $errors = get_errors($form, $min_cost);
                // убираем все значения типа null, валидные значения
                $errors = array_filter($errors);
                var_dump($errors);

                if (!$errors) {
                    $is_set_bet = set_bet($connect, $_SESSION['user']['id'], $lot['id'], $form['cost']);
                    $bets = get_bets_by_lot_id($connect, $id);
                    $cost = !empty($bets) ? find_max_bet($bets)['cost'] : $lot['price_start'];
                    $min_cost = intval($cost) + intval($lot['step_bet']);

                    if (!$is_set_bet) {
                        die(mysqli_error($connect));
                    }

                }
                else {
                    $page_content = include_template($path, [
                        'categories' => $categories,
                        'lot' => $lot,
                        'form' => $form,
                        'bets' => $bets,
                        'cost' => $cost,
                        'min_cost' => $min_cost,
                        'symbol' => RUB_LOWER_CASE,
                        'user_id' => $user_id_max_bet,
                    ]);
                }
            }
        }
    }
}

var_dump($bets);
var_dump($lot);

$page_content = include_template($path, [
    'categories' => $categories,
    'lot' => $lot,
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