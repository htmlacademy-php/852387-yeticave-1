<?php
declare(strict_types=1);
/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var array{id: int, date_end: string, lot_name: string, img_url: string,
 *     description: string, price_start: int, step_bet: int, cat_name: string} $lot
 * * все данные лота по ID лота из БД
 * @var string[] $form заполненные пользователем поля формы
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var array{customer_id: int, lot_id: int, date_add: string, cost: int} $bets все ставки по ID лота из БД
 * @var ?int $cost текущая цена лота
 * @var int $min_cost минимальная ставка по лоту
 * @var string $symbol знак валюты строчный или заглавный (для строкового представления цены в HTML)
 * @var ?string[] $errors массив ошибок по данным из формы
 * @var int $user_id ID пользователя максимальной ставки по лоту
 * @var int $user_id_max_bet
 * @var bool $is_logged
 */
?>

<main>
    <?= include_template('_category.php', ['categories' => $categories]); ?>
    <section class="lot-item container">
        <h2><?= $lot['lot_name']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="..<?= $lot['img_url']; ?>" width="730" height="548" alt="<?= $lot['lot_name']; ?>']?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['cat_name']; ?></span></p>
                <p class="lot-item__description"><?= $lot['description'] ?? ''; ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <?php $timer = get_dt_range($lot['date_end'], false); ?>
                    <div class="lot-item__timer timer <?= $timer['hours'] === 0 ? 'timer--finishing' : '' ?>">
                        <?= timer_format([$timer['hours'], $timer['minutes']]); ?>
                    </div>
                    <div class="lot-item__cost-state">

                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= price_format($cost); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= price_format($min_cost, $symbol); ?></span>
                        </div>
                    </div>
                    <?= include_template('_form-bet.php', [
                        'form' => $form ?? null,
                        'errors' => $errors,
                        'min_cost' => $min_cost,
                        'timer' => $timer,
                        'user_id_max_bet' => $user_id_max_bet,
                        'author_id' => $lot['user_id'],
                        'is_logged' => $is_logged,
                        'user_id' => $user_id
                    ]); ?>
                </div>
                <?= include_template('_history-bets.php', [
                    'bets' => $bets,
                    'min_cost' => $min_cost,
                    'symbol' => $symbol,
                ]); ?>
            </div>
    </section>
</main>