<?php
declare(strict_types=1);
/**
 * @var string[] $categories список категорий лотов
 * @var array{id: string, author_id: string, date_end: string, lot_name: string, cat_name: string, price_start: string, img_url: string, description: string, step_bet: string} $lot
 * * все данные по ID лота из БД
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 */
?>

<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?=htmlspecialchars($category['name']); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?= $lot['lot_name']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="..<?=$lot['img_url']; ?>" width="730" height="548" alt="<?=$lot['lot_name']; ?>']?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['cat_name']; ?></span></p>
                <p class="lot-item__description"><?= $lot['description'] ?? ''; ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <?php $timer=get_dt_range($lot['date_end']); ?>
                    <div class="lot-item__timer timer <?=$timer['hours'] === 0 ? 'timer--finishing' : ''?>">
                        <?=time_format($timer); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <?php if (!empty($bets)): ?>
                            <?php $bet = find_max_bet($bets); ?>
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= price_format(intval($bet['cost'])); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= price_format(intval($bet['cost']) + intval($lot['step_bet'])); ?></span>
                        </div>
                            <?php else: ?>
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= price_format(intval($lot['price_start'])); ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= price_format(intval($lot['price_start'] + intval($lot['step_bet']))) ?></span>
                            </div>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>