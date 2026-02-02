<?php
declare(strict_types=1);
/**
 * @var ?array<int,array{id: int, name: string, code: string} $categories список категорий лотов
 * @var string[] $errors все ошибки заполнения формы пользователем
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
    <form class="form container <?=$class_form; ?>" action="" method="post" autocomplete="off"> <!-- form
    --invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?=isset($errors['email']) ? 'form__item--invalid' : ''?>"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail">
            <span class="form__error"><?=$errors['email'] ?? ''; ?>Введите e-mail</span>
        </div>
        <div class="form__item <?=isset($errors['password']) ? 'form__item--invalid' : ''?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span class="form__error"><?=$errors['password'] ?? ''; ?>Введите пароль</span>
        </div>
        <div class="form__item <?=isset($errors['name']) ? 'form__item--invalid' : ''?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя">
            <span class="form__error"><?=$errors['name'] ?? ''; ?>Введите имя</span>
        </div>
        <div class="form__item <?=isset($errors['message']) ? 'form__item--invalid' : ''?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться"></textarea>
            <span class="form__error"><?=$errors['name'] ?? ''; ?>Напишите как с вами связаться</span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="<?=create_new_url('login.php'); ?>">Уже есть аккаунт</a>
    </form>
</main>
