<?php
declare(strict_types=1);
/**
 * @var string[] $categories список категорий лотов
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var int $hours
 * @var int $minutes
*/
?>

<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--<?= $category['code'] ?>">
            <a class="promo__link" href="pages/all-lots.html"><?=htmlspecialchars($category['name']); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
        <?php $timer=get_dt_range($lot['date_end']);
              [$hours, $minutes]=$timer; ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=$lot['img_url'] ?? ''; ?>" width="350" height="260" alt="<?=htmlspecialchars($lot['lot_name'] ?? ''); ?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=htmlspecialchars($lot['cat_name'] ?? ''); ?></span>
                <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=htmlspecialchars($lot['lot_name'] ?? ''); ?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?=price_format(intval($lot['cost']) ?? 0); ?></span>
                    </div>
                    <div class="lot__timer timer <?=$hours === 0 ? 'timer--finishing' : ''?>">
                        <?=time_format($timer); ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
