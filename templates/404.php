<?php
declare(strict_types=1);

/**
 * @var array<array{name: string, code: string} $categories список категорий лотов
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories]); ?>
    <section class="lot-item container">
        <h2>404 Страница не найдена</h2>
        <p>Данной страницы не существует на сайте.</p>
    </section>
</main>
