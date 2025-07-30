<?php
declare(strict_types=1);
/**
 * @var string[] $categories список категорий лотов
 * @var string[] $lots
 * @var int[] $pages_count количество страниц
 * @var int[] $pages
 * @var int $cur_page текущая (открытая) страница
 * @var string $symbol
 * @var string $search
 * @var string $tab
 * @var string $path
 */
?>
<main>
    <?= include_template('_category.php', ['categories' => $categories]); ?>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
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
