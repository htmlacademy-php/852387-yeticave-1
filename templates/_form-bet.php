<?php
declare(strict_types=1);
/**
 * @var array $timer
 * @var string[] $form заполненные пользователем поля формы
 * @var string[] $errors все ошибки заполнения формы пользователем
 * @var int $min_cost
 * @var int $user_id
 * @var int $lot_id
 */
?>

<?php if ($_SESSION and !is_expiration_date($timer) and !is_identity($user_id, $_SESSION['user']['id'])) : ?>
    <form class="lot-item__form" action="" method="post" autocomplete="off">
        <?php $class_form = isset($errors) ? 'form__item--invalid' : ''; ?>
        <p class="lot-item__form-item form__item <?=$class_form; ?>">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="text" name="cost" placeholder="<?= $min_cost; ?>" value="<?=$form['cost'] ?? ''; ?>">
            <span class="form__error"><?=$errors['cost'] ?? ''; ?></span>
        </p>
        <button type="submit" class="button">Сделать ставку</button>
    </form>
<?php endif; ?>
