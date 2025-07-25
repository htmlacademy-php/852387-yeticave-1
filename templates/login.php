<?php
declare(strict_types=1);
/**
 * @var string[] $categories список категорий лотов
 * @var string[] $user заполненные пользователем поля формы
 * @var string[] $errors все ошибки заполнения формы пользователем
 */
?>
<main>
    <?=include_template('_category.php', ['categories' => $categories]); ?>
    <?php $class_form = isset($errors) ? 'form--invalid' : ''; ?>
    <form class="form container <?=$class_form; ?>" action="" method="post"> <!-- form--invalid -->
      <h2>Вход</h2>
      <div class="form__item <?=isset($errors['email']) ? 'form__item--invalid' : ''?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" <?=$user['email'] ?? ''; ?>>
        <span class="form__error"><?=$errors['email'] ?? ''; ?></span>
      </div>
      <div class="form__item form__item--last <?=isset($errors['password']) ? 'form__item--invalid' : ''?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" <?=$user['password'] ?? ''; ?>>
        <span class="form__error"><?=$errors['password'] ?? ''; ?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
</main>