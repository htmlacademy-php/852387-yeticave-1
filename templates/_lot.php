<?php
declare(strict_types=1);
/**
 * @var array<int,array{name: string, category: string, price: int, img_url: ?string, date_end: string} $lots
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 */
?>

<ul class="lots__list">
    <?php foreach ($lots as $lot): ?>
        <?php $timer=get_dt_range($lot['date_end']); ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=$lot['img_url'] ?? ''; ?>" width="350" height="260" alt="<?=htmlspecialchars($lot['lot_name'] ?? ''); ?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=htmlspecialchars($lot['cat_name'] ?? ''); ?></span>
                <h3 class="lot__title"><a class="text-link" href="<?=create_new_url('lot.php', ['id' => $lot['id']]); ?>"><?=htmlspecialchars($lot['lot_name'] ?? ''); ?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?=price_format(intval($lot['price_start']) ?? 0); ?></span>
                    </div>
                    <div class="lot__timer timer <?=$timer['hours'] === 0 ? 'timer--finishing' : ''?>">
                        <?=time_format($timer); ?>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
