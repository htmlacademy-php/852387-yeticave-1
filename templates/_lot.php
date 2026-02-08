<?php
declare(strict_types=1);

/**
 * @var ?array<int,array{id:int, author_id: int, date_end: string, name: string,
 *                      img_url: string, description: string, price_start: int,
 *                      step_bet: int, cat_name: string, cost: int} $lots массив с параметрами лотов
 * @var array{hours: int, minutes: int, seconds: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут, интервал секунд]
 * @var string $symbol знак валюты строчный или заглавный (для строкового представления цены в HTML)
 */
?>

<ul class="lots__list">
<?php foreach ($lots as $lot): ?>
    <?php $timer = get_dt_range($lot['date_end'], false); ?>
    <?php $cost = $lot['cost'] ?? $lot['price_start']; ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <img src="<?= htmlspecialchars($lot['img_url'] ?? ''); ?>" width="350" height="260"
                 alt="<?= htmlspecialchars($lot['name'] ?? ''); ?>">
        </div>
        <div class="lot__info">
            <span class="lot__category"><?= htmlspecialchars($lot['cat_name'] ?? ''); ?></span>
            <h3 class="lot__title"><a class="text-link" href="<?=create_new_url('lot.php',
                    ['id' => $lot['id']]); ?>"><?= htmlspecialchars($lot['name'] ?? ''); ?></a></h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?= price_format($lot['price_start'], $symbol)?? 0; ?></span>
                </div>
                <div class="lot__timer timer <?=$timer['hours'] <= 0 ? 'timer--finishing' : ''; ?>">
                    <?=timer_format([$timer['hours'], $timer['minutes']]); ?>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; ?>
</ul>
