<?php
declare(strict_types=1);

require_once ('helpers.php');
$db = require_once('db.php');

/**
 * @var string[] $categories
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 *
 **/

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, 'utf8');

$categories = [];
$lots = [];