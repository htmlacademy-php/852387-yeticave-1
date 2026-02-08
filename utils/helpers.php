<?php
declare(strict_types=1);

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt(mysqli $link, string $sql, array $data = []): mysqli_stmt
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественного числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    return match (true) {
        $mod100 >= 11 && $mod100 <= 20 => $many,
        $mod10 > 5 => $many,
        $mod10 === 1 => $one,
        $mod10 >= 2 && $mod10 <= 4 => $two,
        default => $many,
    };
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = []): string
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}

/**
 * Создает новую ссылку с данными параметрами
 * @var string $path адрес данной страницы
 * @var array $data требуемые значения параметров, которые нужно заменить/добавить в $_GET
 * @return string новый адрес ссылки: адрес страницы + строка запроса
 **/
function create_new_url(string $path, array $data = []): string
{
    $params = [];

    if (empty($data)) {
        return "/$path";
    }

    foreach ($data as $key => $value) {
        $params[$key] = $value;
    }

    $query = http_build_query($params);
    return "/$path?$query";
}

/**
 * Возвращает отфильтрованный массив значений заданных в случае успеха или false в случае неудачи
 * @param string $name данное имя поля
 * @return mixed
 **/
function get_post_value(string $name): mixed
{
    return filter_input(INPUT_POST, $name, FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
* Возвращает массив с данными [кол-во страниц, смещение, массив с номерами страниц]
* @param int $cur_page номер текущей страницы
* @param int $items_count количество элементов
* @param int $page_items количество элементов на странице
* @return int[] [кол-во страниц, смещение, массив с номерами страниц]
*/
function get_data_pagination(int $cur_page, int $items_count, int $page_items): array
{
    //считаем кол-во страниц и смещение
    $pages_count = (int)ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    //заполняем массив номерами всех страниц
    $pages = range(1, $pages_count);
    return [$pages_count, $offset, $pages];
}

/**
 * Возвращает TRUE, если ID пользователей совпадают
 *
 * @param ?int $user_id_1 ID одного пользователя
 * @param ?int $user_id_2 ID второго пользователя
 *
 * @return boolean
 **/
function is_identity(?int $user_id_1, ?int $user_id_2): bool
{
    return $user_id_1 === $user_id_2;
}

/**
 * Возвращает опции таймера ставок для страницы ставок авторизованного пользователя
 *
 * @param string $date_end дата завершения ставок по лоту
 * @param ?int $user_id ID авторизованного пользователя
 * @param ?int $user_win_id ID пользователя выигрышной ставки
 * @return string[] [флаг для класса CSS, информационное определение таймера]
 */
function get_bets_timer_options(string $date_end, ?int $user_id, ?int $user_win_id): array
{
    $timer = get_dt_range($date_end, false);
    if (is_expiration_date($timer)) {
        if (is_identity($user_id, $user_win_id)) {
            $flag = 'win';
            $button = 'Ставка выиграла';
        } else {
            $flag = 'end';
            $button = 'Торги окончены';
        }
    } else {
        $flag = $timer['hours'] < 1 ? 'finishing' : '';
        $button = timer_format($timer);
    }
    return [$flag, $button];
}
