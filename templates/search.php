<?php
declare(strict_types=1);
/**
 * @var array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?array<int,array{id: int, author_id: int, date_end: string, lot_name: string, img_url: string,
 *       decription: string, price_start: int, step_bet: int, cat_name: string} $lots массив данных лотов из БД
 * @var int $pages_count количество страниц
 * @var int[] $pages массив с номерами страниц
 * @var int $cur_page текущая (открытая) страница
 * @var string $symbol знак валюты строчный или заглавный (для строкового представления цены в HTML)
 * @var string $search значение из поля поиска
 * @var string $tab название параметра для строки запроса ('category' or 'search')
 * @var string $path путь на нужную страницу для строки запроса ('all-lots.php' or 'search.php')
 */
?>
<main>
    <?= include_template('_category.php', ['categories' => $categories]); ?>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search ?? ''); ?></span>»</h2>
            <?php if (count($lots) > 0): ?>
                <?= include_template('_lot.php', ['lots' => $lots, 'symbol' => $symbol]); ?>
            <?php else: ?>
                <p>Ничего не найдено по вашему запросу</p>
            <?php endif; ?>
        </section>
        <?= include_template('_pagination.php', [
            'pages' => $pages,
            'pages_count' => $pages_count,
            'cur_page' => (int)$cur_page,
            'tab' => $tab,
            'path' => $path,
            'value' => $search,
        ]); ?>
    </div>
</main>
