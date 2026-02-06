<?php
declare(strict_types=1);

require_once ('init.php');
require_once ('models/bets.php');
require_once ('utils/date-time.php');
require_once ('utils/price.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array $bets
 * @var ?array $lots
 * @var ?int $user_id
 * @var string $content содержимое шаблона страницы, в который передаем нужные ему данные
 */

const RUB_LOWER_CASE = 'RUB_LOWER_CASE';

if(!isset($_SESSION['user'])) {
    http_response_code(403);
    exit;
}

$title = 'Мои ставки';

$user_id = $_SESSION['user']['id'] ?? null;

if ($user_id) {
    // все ставки авторизованного пользователя из БД
    $bets = get_bets_by_user_id($connect, $user_id);
}

$content = include_template('my-bets.php', [
    'categories' => $categories,
    'bets' => $bets,
    'user_id' => $user_id,
    'symbol' => RUB_LOWER_CASE,
]);

$layout = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout);
