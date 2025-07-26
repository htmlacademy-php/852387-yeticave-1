<?php
declare(strict_types=1);
/**
 * @var string[] $categories список категорий лотов
 * @var string $cat_name название категории
 * @var string[] $lots
 * @var int[] $pages_count количество страниц
 * @var int[] $pages
 * @var int $cur_page текущая (открытая) страницв
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories]); ?>
    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?=$cat_name; ?>»</span></h2>
            <?php if (count($lots) > 0): ?>
                <?=include_template('_lot.php', ['lots' => $lots]); ?>
            <?php else: ?>
                <p>В этой категории нет лотов</p>
            <?php endif; ?>
        </section>
        <?=include_template('_pagination.php', [
            'pages' => $pages,
            'pages_count' => $pages_count,
            'cur_page' => $cur_page
        ]); ?>
    </div>
</main>
