<?php
declare(strict_types=1);
/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories все категории из БД
 * @var ?array{email: string, password: string} $form заполненные пользователем поля формы
 * @var ?array{email: string, password: string} $errors все ошибки по полям формы, заполненной пользователем
 */
?>
<main>
    <?= include_template('_category.php', ['categories' => $categories]); ?>
    <?php $class_form = isset($errors) ? 'form--invalid' : ''; ?>
    <form class="form container <?= $class_form; ?>" action="" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>">
            <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email"
                   placeholder="Введите e-mail" <?= htmlspecialchars($form['email'] ?? ''); ?>>
            <span class="form__error"><?= $errors['email'] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--last <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password"
                   placeholder="Введите пароль" <?= htmlspecialchars($form['password'] ?? ''); ?>>
            <span class="form__error"><?= $errors['password'] ?? ''; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>