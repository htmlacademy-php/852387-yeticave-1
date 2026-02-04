<?php
declare(strict_types=1);

/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var ?array $form заполненные пользователем поля формы
 * @var ?array $errors все ошибки заполнения формы пользователем
 */
?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?=htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php $class_form = isset($errors) ? 'form--invalid' : ''; ?>
    <form class="form container <?=$class_form; ?>" action="" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <div class="form__item <?=isset($errors['email']) ? 'form__item--invalid' : ''?>"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" <?=$form['email'] ?? ''; ?>>
            <span class="form__error"><?=$errors['email'] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--last <?=isset($errors['password']) ? 'form__item--invalid' : ''?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" <?=$form['password'] ?? ''; ?>>
            <span class="form__error"><?=$errors['password'] ?? ''; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
