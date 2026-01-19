<?php
declare(strict_types=1);

require_once ('utils/price.php');
require_once ('utils/date_time.php');

/**
 * @var array<int,array{name: string, category: string, price: int, url: string, date_end: string} $lots массив с параметрами лотов
 */
?>

<?php foreach ($lots as $lot): ?>
<?php $time = time_diff($lot['date_end']); ?>
    <li class="lots__item lot">
        <div class="lot__image">
            <img src="<?= htmlspecialchars($lot['url'] ?? ''); ?>" width="350" height="260" alt="">
        </div>
        <div class="lot__info">
            <span class="lot__category"><?= htmlspecialchars($lot['category'] ?? ''); ?></span>
            <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($lot['name'] ?? ''); ?></a></h3>
            <div class="lot__state">
                <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?= price_format($lot['price'] ?? 0); ?></span>
                </div>
                <div class="lot__timer timer <?=$time['hours'] <= 0 ? 'timer--finishing' : ''; ?>">
                    <?= time_format($time); ?>
                </div>
            </div>
        </div>
    </li>
<?php endforeach; ?>