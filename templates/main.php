<?php

/**
 * @var string[] $categories массив названий категорий
 * @var array<int,array{name: string, category: string, price: int, url: string} $lots массив с параметрами лотов
 */

?>

<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--boards">
            <a class="promo__link" href="pages/all-lots.html"><?= htmlspecialchars($category); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
       <?=include_template('lot.php', ['lots' => $lots]); ?>
    </ul>
</section>
