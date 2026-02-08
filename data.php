<?php
declare(strict_types=1);

require_once ('models/categories.php');

/**
 * @var string $title заголовок страницы сайта
 * @var boolean|mysqli|object $connect ресурс соединения с сервером БД
 * @var ?array<array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?array<int,array{name: string, category: string, price: int, url: string, date_end: string} $lots массив с параметрами лотов
 * @var ?array{id: int, author_id: int, date_add: string, name: string, description: string, img_url: string, price_start: int, step_bet: int, cat_name: string} $lot все данные по ID лота из БД
 * @var ?array<int,array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 * @var ?array<int,array{id: int, date_add: string, name: string, email: string, password: string, contact: string} $users массив с параметрами по всем users из БД
 * @var ?array $form заполненные пользователем поля формы
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var ?int $cost текущая цена лота
 * @var ?array $data массив с данными [ID лота и данные лота по ID из БД]
 * @var int $user_id_max_bet ID пользователя максимальной ставки по лоту
 * @var int $min_cost минимальная ставка по лоту
 * @var string $content HTML-код - контент страницы
 */

// лимит кол-ва элементов
const LIMIT_ITEMS = 10;

// начальные данные
$title = 'Главная';
// выполнение запроса на список категорий
$categories = get_categories($connect);
$users = null;
$lots = null;
$bets = null;
$lot = null;
$errors = null;
$form = null;
$content = '';
$pages = null;
$pages_count = null;
$cur_page = 1;
$cat_name = null;
$cat_id = null;
$items_count = null;
$path = '';
$tab = '';
$cost = 0;
$user_id_max_bet = null;
$min_cost = 0;
$is_logged = false;
$user_id = null;
$is_author = false;
$is_user_max_bet = false;
