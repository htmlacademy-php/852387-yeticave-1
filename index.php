<?php
declare(strict_types=1);

require_once('init.php');
require_once('utilities/helpers.php');
require_once('utilities/date-time.php');
require_once('models/lots.php');

/**
 * @var string $title заголовок страницы сайта
 * @var false|mysqli $connect mysqli Ресурс соединения
 * @var ?array<int,array{id: string, name: string, code: string} $categories все категории из БД
 * @var ?array<int,array{id: string, lot_name: string, cat_name: string, cost: string,
 *     price_start: string, img_url: ?string, date_end: string} $lots все новые лота из БД
 */

const RUB_UPPER_CASE = 'RUB_UPPER_CASE';

$lots = get_lots($connect);

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots,
    'symbol' => RUB_UPPER_CASE,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $title,
    'categories' => $categories,
]);

print($layout_content);

require('getwinner.php');
