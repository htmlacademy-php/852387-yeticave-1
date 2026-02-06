<?php
declare(strict_types=1);

/**
 * @var array<array{id: int, name: string, code: string} $categories список категорий лотов
 * @var array<int,array{name: string, category: string, price: int, url: string, date_end: string} $lots массив с параметрами лотов
 * @var string $symbol
 */

?>
<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?= htmlspecialchars($category['code']);?>">
                <a class="promo__link" href="<?=create_new_url('all-lots.php',
                    ['category' => $category['id']]); ?>"><?= htmlspecialchars($category['name']); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>

        <?=include_template('_lot.php', ['lots' => $lots, 'symbol' => $symbol]); ?>

    </section>
</main>
