<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('models/bets.php');
require_once ('utils/date-time.php');
require_once ('utils/price.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var ?array<int,array{lot_id: string, cost: string, date_add: string, date_end_lot: string,
 *              lot_name: string, img_url: string, user_win_id: string, cat_name: string,
 *              author_contact: string} $bets все ставки пользователя
 * @var ?int $user_id ID авторизованного пользователя
 * @var ?string $cat_name название категории
 * @var string $content содержимое шаблона страницы, в который передаем нужные ему данные
 */

const RUB_LOWER_CASE = 'RUB_LOWER_CASE';

if(!isset($_SESSION['user'])) {
    http_response_code(403);
    exit;
}

$title = 'Мои ставки';
$user_id = $_SESSION['user']['id'] ?? null;
$bets = $user_id ? get_bets_by_user_id($connect, $user_id) : null;

$content = include_template('my-bets.php', [
    'categories' => $categories,
    'bets' => $bets,
    'user_id' => $user_id,
    'symbol' => RUB_LOWER_CASE,
    'cat_name' => $cat_name,
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
    'cat_name' => $cat_name,
]);

print($layout);
