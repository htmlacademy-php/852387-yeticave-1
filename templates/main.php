<?php
declare(strict_types=1);
/**
 * @var string[] $categories список категорий лотов
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
*/
?>

<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= $category['code'] ?? '' ?>">
                <a class="promo__link" href="<?=create_new_url('all-lots.php', ['category' => $category['id']]); ?>"><?=htmlspecialchars($category['name']); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <?=include_template('_lot.php', ['lots' => $lots]); ?>
    </section>
</main>
