<?php
declare(strict_types=1);

/**
 * @var ?array<array{id: int, name: string, code: string} $categories список категорий лотов из БД
 * \@var ?string $cat_name название категории
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories, 'cat_name' => $cat_name]) ?>
    <section class="lot-item container">
        <h2>404 Страница не найдена</h2>
        <p>Данной страницы не существует на сайте.</p>
    </section>
</main>
