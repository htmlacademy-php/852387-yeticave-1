-- добавляем в таблицу categories существующие категории объявлений (лотов)
INSERT categories(name, code) VALUE ('Доски и лыжи', 'boards'),
    ('Крепления', 'attachment'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothing'),
    ('Инструменты', 'tools'),
    ('Разное', 'other');

-- добавляем в таблицу users данные нескольких выдуманных пользователей
INSERT INTO users(name, email, password, contact)
VALUES ('Елена', 'stor@internet.ru', 'asJHfd4UE3m', 'телефон 8450934324'),
       ('Егор', 'smir@yandex.ru', 'wHyt23bvyK', 'telegram @smita'),
       ('Макс', 'frik@mail.ru', 'dge7jMN4', 'telegram @frick');

-- добавляем в таблицу lots существующий список объявлений (лотов)
INSERT INTO lots(user_id, name, description, img_url, price, date_end, step_bet, cat_id)
VALUES (1,
        '2014Rossignol District Snowboard',
        '',
        '/img/lot-1.jpg',
        10999,
        '2026-02-21',
        100,
        1),
       (2,
        'DC Ply Mens 2016/2017 Snowboard',
        '',
        '/img/lot-2.jpg',
        159999,
        '2026-02-05',
        120,
        1),
       (2,
        'Крепления Union Contact Pro 2015 года размер L/XL',
        '',
        '/img/lot-3.jpg',
        8000,
        '2026-02-01',
        150,
        2),
       (2,
        'Ботинки для сноуборда DC Mutiny Charocal',
        'без дефектов',
        '/img/lot-4.jpg',
        10999,
        '2026-02-10',
        250,
        3),
       (1,
        'Куртка для сноуборда DC Mutiny Charocal',
        'немного б/у, без пятен, замки все работают',
        '/img/lot-5.jpg',
        7500,
        '2026-02-03',
        200,
        4),
       (1,
        'Маска Oakley Canopy',
        'новая',
        '/img/lot-6.jpg',
        5400,
        '2026-01-08',
        100,
        6);

-- добавляем пару ставок для любого объявления
INSERT INTO bets(user_id, lot_id, cost)
VALUES (2, 6, 5500),
       (3, 6, 5600),
       (2, 5, 7700),
       (3, 3, 8300),
       (1, 3, 8150);

-- получаем все категории из таблицы categories;
SELECT *  FROM categories;

-- Получаем самые новые, открытые лоты. Каждый лот включает:
-- название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT  l.id,
        l.date_end,
        l.name 'lot_name',
        price 'price_start',
        img_url,
        MAX(b.cost) as 'cost',
        c.name 'cat_name'
FROM lots l
         LEFT JOIN bets b ON b.lot_id = l.id
         INNER JOIN categories c ON l.cat_id = c.id
WHERE l.date_end > DATE(NOW())
group by l.id, l.date_add
ORDER BY l.date_add DESC LIMIT 10;

-- Показываем лот по его ID (например id = 1). Получаем также название категории, к которой принадлежит лот;
SELECT l.*, c.name 'cat_name' FROM lots l
                                       INNER JOIN categories c ON l.cat_id = c.id
WHERE l.id = 1;

-- обновляем название лота по его идентификатору;
UPDATE lots
SET name = 'Ботинки'
WHERE id = 4;
-- проверяем изменения в таблице
SELECT *, c.name 'cat_name' FROM lots l
    INNER JOIN categories c ON l.cat_id = c.id
WHERE l.id = 4;


-- получаем список ставок для лота по его id (например id = 6)с сортировкой по дате.
SELECT user_id "customer_id",
       lot_id,
       date_add,
       cost FROM bets
WHERE lot_id = 6
ORDER BY bets.date_add;
-- тоже получаем список ставок для лота по его id (например id = 3)
-- с сортировкой по дате с использованием JOIN.
SELECT b.* FROM bets b
                    INNER JOIN lots l ON l.id = b.lot_id
WHERE l.id = 6
ORDER BY b.date_add;

SELECT user_id FROM bets WHERE cost = (SELECT max(cost) from bets where lot_id = 6);

SELECT lot_id, max(cost) as cost, date_add FROM bets
WHERE user_id = 4
GROUP BY lot_id , date_add
ORDER BY date_add DESC;

SELECT l.id,
       l.date_end,
       l.name,
       l.img_url,
       l.user_win_id,
       c.name "cat_name",
       u.contact "author_contact" FROM lots l
                                         INNER JOIN categories c ON l.cat_id = c.id
                                         INNER JOIN users u ON l.user_id = u.id
WHERE l.id IN (4, 6);

SELECT b.lot_id, MAX(b.cost), b.date_add "date_add",
       l.date_end "date_end_lot", l.name "lot_name",
       l.img_url, l.user_win_id,
       c.name "cat_name",
       u.contact "author_contact" FROM bets b
                                         INNER JOIN lots l on b.lot_id = l.id
                                         INNER JOIN categories c on l.cat_id = c.id
                                         INNER JOIN users u ON l.user_id = u.id
WHERE b.user_id = 4
GROUP BY b.lot_id , b.date_add
ORDER BY b.date_add DESC;
