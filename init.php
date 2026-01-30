<?php
declare(strict_types=1);

$db = require_once('db.php');

$connect = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($connect, 'utf8');