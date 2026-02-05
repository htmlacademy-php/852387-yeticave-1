<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('data.php');
require_once ('utils/helpers.php');
require_once ('utils/db.php');
require_once ('models/lots.php');
require_once ('models/bets.php');
require_once ('utils/date-time.php');
require_once ('utils/price.php');
require_once ('validate/bet.php');

/**
 * @var string $title заголовок страницы сайта
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var array{id: int, author_id: int, date_add: string, name: string, description: string, img_url: string, price_start: int, step_bet: int, cat_name: string} $lot все данные по ID лота из БД
 * @var array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 * @var string $content HTML-код - контент страницы
 * @var string $layout весь HTML-код страницы с подвалом и шапкой
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
                    $bets = get_bets_by_id($connect, $id);
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

$content = include_template($path, [
    'categories' => $categories,
    'lot' => $lot,
    'bets' => $bets,
    'cost' => $cost,
    'user_id' => $user_id_max_bet,
    'min_cost' => $min_cost,
    'symbol' => RUB_LOWER_CASE,
    'errors' => $errors,
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout);
