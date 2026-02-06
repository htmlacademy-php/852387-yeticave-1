<?php
declare(strict_types=1);

/**
 * @var ?array{cost: int} $form заполненные пользователем поля формы
 * @var ?array{cost: string} $errors все ошибки заполнения формы пользователем
 * @var int $min_cost минимальная ставка по лоту
 * @var array{hours: int, minutes: int} $timer кол-во времени до конечной даты [интервал часов, интервал минут]
 * @var bool $is_logged пользователь авторизован на сайте
 * @var bool $is_author пользователь является автором лота
 * @var bool $is_user_max_bet пользователь является автором последней ставки по лоту
 */
?>

<?php if ($is_logged and !is_expiration_date($timer) and !$is_author and !$is_user_max_bet) : ?>
    <form class="lot-item__form" action="" method="post" autocomplete="off">
        <?php $class_form = isset($errors) ? 'form__item--invalid' : ''; ?>
        <p class="lot-item__form-item form__item <?= $class_form; ?>">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="text" name="cost" placeholder="<?= $min_cost; ?>"
                   value="<?= (int)htmlspecialchars($form['cost'] ?? ''); ?>">
            <span class="form__error"><?= $errors['cost'] ?? ''; ?></span>
        </p>
        <button type="submit" class="button">Сделать ставку</button>
    </form>
<?php endif; ?>
