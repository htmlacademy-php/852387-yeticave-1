<?php
declare(strict_types=1);

/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var ?string $cat_name название категории
 * @var array{id: int, date_end: string, name: string, img_url: string,
 *     description: string, price_start: int, step_bet: int, cat_name: string} $lot
 * * все данные лота по ID лота из БД
 * @var ?array{cost: int} $form заполненные пользователем поля формы
 * @var array{hours: int, minutes: int, seconds: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут, интервал секунд]
 * @var array{customer_id: int, lot_id: int, date_add: string, cost: int} $bets все ставки по ID лота из БД
 * @var int $cost текущая цена лота
 * @var int $min_cost минимальная ставка по лоту
 * @var string $symbol знак валюты строчный или заглавный (для строкового представления цены в HTML)
 * @var ?array{cost: string} $errors все ошибки заполнения формы пользователем
 * @var bool $is_logged пользователь авторизован на сайте
 * @var bool $is_author пользователь является автором лота
 * @var bool $is_user_max_bet пользователь является автором последней ставки по лоту
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories, 'cat_name' => $cat_name]); ?>
    <section class="lot-item container">
        <h2><?=htmlspecialchars($lot['name'] ?? ''); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="..<?=htmlspecialchars($lot['img_url'] ?? ''); ?>" width="730" height="548" alt="<?=htmlspecialchars($lot['name'] ?? ''); ?>']?>">
                </div>
                <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['cat_name'] ?? ''); ?></span></p>
                <p class="lot-item__description"><?=htmlspecialchars($lot['description'] ?? ''); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <?php $timer = get_dt_range($lot['date_end'], false); ?>
                    <div class="lot-item__timer timer <?= $timer['hours'] === 0 ? 'timer--finishing' : ''?>">
                        <?=timer_format([$timer['hours'], $timer['minutes']]); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=price_format($cost); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?=price_format($min_cost, $symbol); ?></span>
                        </div>
                    </div>
                    <?=include_template('_form-bet.php', [
                        'form' => $form ?? null,
                        'errors' => $errors,
                        'min_cost' => $min_cost,
                        'timer' => $timer,
                        'is_author' => $is_author,
                        'is_logged' => $is_logged,
                        'is_user_max_bet' => $is_user_max_bet,
                    ]); ?>
                </div>
                <?=include_template('_history-bets.php', [
                    'bets' => $bets,
                    'min_cost' => $min_cost,
                    'symbol' => $symbol,
                ]); ?>
            </div>
    </section>
</main>
