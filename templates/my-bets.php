<?php
declare(strict_types=1);

/**
 * @var array<array{id: int, name: string, code: string} $categories список категорий лотов
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var string $symbol знак валюты для строкового форматирования цена
 * @var ?int $user_id авторизованный пользователь
 * @var ?array<int,array{lot_id: int, cost: int, date_add: string, date_end: string,
 *              lot_name: string, img_url: string, user_win_id: int, cat_name: string,
 *              author_contact: string} $bets все ставки по ID пользователя из БД
 * @var ?string $cat_name название категории
 **/
?>


<main>
    <?=include_template('_category.php', ['categories' => $categories, 'cat_name' => $cat_name]); ?>
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
                        <h3 class="rates__title"><a href="<?= create_new_url('lot.php',
                                ['id' => $bet['lot_id']]); ?>"><?=$bet['lot_name']; ?></a></h3>
                        <?php if(is_identity($user_id, $bet['user_win_id'])): ?>
                            <p><?= $bet['author_contact']; ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?=$bet['cat_name']; ?>
                </td>
                <td class="rates__timer">
                    <?php [$flag, $button] = get_bets_timer_options($bet['date_end'], $user_id, $bet['user_win_id']); ?>
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
