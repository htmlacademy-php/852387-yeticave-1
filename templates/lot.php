<?php
declare(strict_types=1);

/**
 * @var array<array{name: string, code: string} $categories список категорий лотов
 * @var array{id: string, author_id: string, date_end: string, lot_name: string,
 *      cat_name: string, price_start: string, img_url: string, description: string, step_bet: string} $lot все данные по ID лота из БД
 * @var string[] $form заполненные пользователем поля формы
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var array{customer_id: string, lot_id: string, date_add: string, cost: string} $bets все ставки по ID лота из БД
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories]); ?>
    <section class="lot-item container">
        <h2><?= $lot['name']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="..<?=$lot['img_url']; ?>" width="730" height="548" alt="<?=$lot['name']; ?>']?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['cat_name']; ?></span></p>
                <p class="lot-item__description"><?= $lot['description'] ?? ''; ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <?php $timer = get_dt_range($lot['date_end']); ?>
                    <div class="lot-item__timer timer <?= $timer['hours'] === 0 ? 'timer--finishing' : ''?>">
                        <?= timer_format($timer); ?>
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
                    <?=include_template('_form-bet.php', [
                        'form' => $form ?? null,
                        'errors' => $errors,
                        'min_cost' => $min_cost,
                        'timer' => $timer,
                        'user_id' => $user_id,
                        'lot_id' => $lot['id'],
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
