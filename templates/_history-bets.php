<?php
declare(strict_types=1);
/**
 * @var array $bets список всех ставок
 * @var string $symbol заглавный или строчный знак валюты
 **/
?>

<div class="history">
    <h3>История ставок (<span><?=count($bets); ?></span>)</h3>
    <table class="history__list">
        <?php foreach ($bets as $bet): ?>
            <tr class="history__item">
                <td class="history__name"><?=$bet['user_name']; ?></td>
                <td class="history__price"><?=price_format($bet['cost'], $symbol); ?></td>
                <td class="history__time"><?=history_time_format($bet['date_add']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
