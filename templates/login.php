<?php
declare(strict_types=1);

/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?array $form заполненные пользователем поля формы
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var ?string $cat_name название категории
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories, 'cat_name' => $cat_name]); ?>
    <?php $class_form = isset($errors) ? 'form--invalid' : ''; ?>
    <form class="form container <?=$class_form; ?>" action="" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <div class="form__item <?=isset($errors['email']) ? 'form__item--invalid' : ''; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" <?=$form['email'] ?? ''; ?>>
            <span class="form__error"><?=$errors['email'] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--last <?=isset($errors['password']) ? 'form__item--invalid' : ''; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" <?=$form['password'] ?? ''; ?>>
            <span class="form__error"><?=$errors['password'] ?? ''; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
