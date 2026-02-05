<?php
declare(strict_types=1);

/**
 * Получает список пользователей или завершаем код с ошибкой
 *
 * @param mysqli $connect ресурс соединения с сервером БД
 * @return ?array<int,array{id: string, date_add: string, name: string, password: string, contact: string}
 */
function get_users(mysqli $connect): ?array
{
    $sql = 'SELECT * FROM `users`';
    return get_items($connect, $sql);
}

/**
 * Формирует и выполняет SQL-запрос на добавление нового пользователя
 *
 * @param mysqli $connect Ресурс соединения
 * @param string[] $data данные для добавления лота в БД
 * @return boolean
 **/

function set_user(mysqli $connect, array $data): bool
{
    $sql = 'INSERT INTO users(name, email, password, contact)
                VALUES (?, ?, ?, ?)';
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = db_get_prepare_stmt($connect, $sql, $data);
    return mysqli_stmt_execute($stmt);
}

/**
 * Получает данные пользователя по EMAIL из таблицы БД
 *
 * @param mysqli $connect Ресурс соединения
 * @param string $email данный EMAIL
 * @return ?array{id: string, date_add: string, name: string, email: string, password: string, contact: string}
 **/
function get_user_by_email(mysqli $connect, string $email): ?array
{
    $sql = "SELECT u.id,
                u.date_add,
                u.name,
                u.email,
                u.password,
                u.contact,
                l.id 'lot_id' FROM users u
                    INNER JOIN lots l on u.id = l.user_id
                              WHERE email = ?";

    return get_item($connect, $sql, $email);
}

//stora@internet.ru  пароль 111111
