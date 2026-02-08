<?php
declare(strict_types=1);

/**
 * @var ?array<array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?string $cat_name название категории
 * @var ?array<int,array{id: int, author_id: int, date_end: string, name: string,
 *           img_url: string, decription: string, price_start: int, step_bet: int, cat_name: string,
 *           cost: int} $lots массив данных лотов из БД
 * @var ?int $count_lots количество лотов по поисковому запросу
 * @var ?string $cat_name название категории
 * @var int $pages_count количество страниц
 * @var ?int[] $pages массив с номерами страниц
 * @var int $cur_page текущая (открытая) страница
 * @var string $tab название параметра для строки запроса ('category' or 'search')
 * @var string $path путь на нужную страницу для строки запроса ('all-lots.php' or 'search.php')
 * @var int $cat_id ID категории
 * @var string $symbol знак валюты строчный или заглавный (для строкового представления цены в HTML)
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories, 'cat_name' => $cat_name]); ?>
    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?=$cat_name; ?>»</span></h2>
            <?php if ($count_lots > 0): ?>
                <?=include_template('_lot.php', ['lots' => $lots, 'symbol' => $symbol]); ?>
            <?php else: ?>
                <p>В этой категории нет лотов</p>
            <?php endif; ?>
        </section>
        <?=include_template('_pagination.php', [
            'pages' => $pages,
            'pages_count' => $pages_count,
            'cur_page' => $cur_page,
            'tab' => $tab,
            'path' => $path,
            'value' => $cat_id
        ]); ?>
    </div>
</main>
