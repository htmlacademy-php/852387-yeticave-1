<?php
declare(strict_types=1);
/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 */
?>

<main>
    <?= include_template('_category.php', ['categories' => $categories]); ?>
    <section class="lot-item container">
        <h2>404 Страница не найдена</h2>
        <p>Данной страницы не существует на сайте.</p>
    </section>
</main>
