<?php
declare(strict_types=1);

/**
 * @var int $pages_count количество страниц
 * @var int[] $pages массив номеров страниц
 * @var int $cur_page текущая (открытая) страница
 * @var string $tab название параметра для строки запроса ('category' or 'search')
 * @var string $path путь на нужную страницу для строки запроса ('all-lots.php' or 'search.php')
 * @var ?string $value значение параметра для строки запроса
 */
?>

<?php if ($pages_count): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href="<?= $cur_page > 1 ? create_new_url($path, [$tab => $value, 'page' => $cur_page - 1]) : ''; ?>">Назад</a></li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?= $page === (int)$cur_page ? 'pagination-item-active' : ''; ?>">
                <a href="<?= create_new_url($path, [$tab => $value, 'page' => $page]); ?>"><?= $page; ?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a href="<?= $cur_page < $pages_count ? create_new_url($path, [$tab => $value, 'page' => $cur_page + 1]) : ''; ?>">Вперед</a></li>
    </ul>
<?php endif; ?>
