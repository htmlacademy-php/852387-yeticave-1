<?php
declare(strict_types=1);

/**
 * название лота (ссылка на страницу);
 * сумма ставки;
 * дата/время.
 * Выигравшие ставки должны выделяться
 * отдельным цветом, а также там должны быть размещены контакты владельца лота.
 * @var string[] $categories список категорий лотов
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var string $symbol знак валюты для строкового форматирования цена
 * @var ?int $user_id авторизованный пользователь
 * @var ?array<int,array{lot_id: string, cost: string, date_add: string,
 *      date_end_lot: string, lot_name: string, img_url: string, user_win_id: string,
 *      cat_name: string, author_contact: string} $bets все ставки по ID пользователя из БД
 **/
?>


<main>
    <?=include_template('_category.php', ['categories' => $categories]); ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <?php if (isset($bets)): ?>
        <table class="rates__list">
            <?php foreach ($bets as $bet): ?>
            <tr class="rates__item <?= is_identity($user_id, $bet['user_win_id']) ? 'rates__item--win' : ''; ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?=$bet['img_url'] ?? ''; ?>" width="54" height="40" alt="<?=$bet['lot_name']; ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="<?= create_new_url('lot.php', ['id' => $bet['lot_id']]); ?>"><?=$bet['lot_name']; ?></a></h3>
                        <?php if(is_identity($user_id, $bet['user_win_id'])): ?>
                            <p><?= $bet['author_contact']; ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?=$bet['cat_name']; ?>
                </td>
                <td class="rates__timer">
                    <?php [$flag, $button] = get_bets_timer_options($bet['date_end_lot'], $user_id, $bet['user_win_id']); ?>
                    <div class="timer timer--<?= $flag; ?>"><?= $button; ?></div>
                </td>
                <td class="rates__price">
                    <?= price_format($bet['cost'], $symbol); ?>
                </td>
                <td class="rates__time">
                    <?= bet_time_format($bet['date_add']); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </section>
</main>
