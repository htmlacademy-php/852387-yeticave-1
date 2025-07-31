<?php
declare(strict_types=1);
/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var ?array<int,array{id: int, date_end: string, lot_name: string, price_start: int,
 *       img_url: string, cost: int, cat_name: string} $lots
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var string $symbol знак валюты строчный или заглавный (для строкового представления цены в HTML)
 */
?>

<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
            снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--<?= htmlspecialchars($category['code'] ?? ''); ?>">
                    <a class="promo__link"
                       href="<?= create_new_url('all-lots.php',
                           ['category' => $category['id']]); ?>"><?= htmlspecialchars($category['name'] ?? ''); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <?= include_template('_lot.php', ['lots' => $lots, 'symbol' => $symbol]); ?>
    </section>
</main>
