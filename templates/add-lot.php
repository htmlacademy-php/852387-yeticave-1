<?php
declare(strict_types=1);

/**
 * @var array<array{id:int, name: string, code: string} $categories список категорий лотов
 * @var ?array $errors все ошибки заполнения формы пользователем
 * @var ?array $form заполненные пользователем поля формы
 */
?>

<main>
    <?=include_template('_category.php', ['categories' => $categories]); ?>
    <?php $class_form = isset($errors) ? 'form--invalid' : ''; ?>
    <form class="form form--add-lot container <?=$class_form; ?>" action="" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?=isset($errors['name']) ? 'form__item--invalid' : ''?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?=$form['name'] ?? ''; ?>">
                <span class="form__error"><?=$errors['name'] ?? ''; ?></span>
            </div>
            <div class="form__item <?=isset($errors['cat_id']) ? 'form__item--invalid' : ''; ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="cat_id">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?=$category['id']; ?>"
                                <?php if ($category['id'] === get_post_value('cat_id')): ?>selected<?php endif; ?>>
                            <?=$category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?=isset($errors['cat_id']) ?? ''; ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <?=isset($errors['description']) ? 'form__item--invalid' : ''; ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"><?=$form['description'] ?? ''?></textarea>
            <span class="form__error"><?=$errors['description'] ?? ''; ?></span>
        </div>
        <div class="form__item form__item--file <?=isset($errors['file']) ? 'form__item--invalid' : ''; ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="lot_img" value="<?=$form['img_url'] ?? ''; ?>">
                <label for="lot-img">
                    Добавить
                </label>
                <span class="form__error"><?=$errors['file'] ?? ''; ?></span>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?=isset($errors['price']) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="price" placeholder="0" value="<?=$form['price'] ?? ''; ?>">
                <span class="form__error"><?=$errors['price'] ?? ''; ?></span>
            </div>
            <div class="form__item form__item--small <?=isset($errors['step_bet']) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="step_bet" placeholder="0" value="<?=$form['step_bet'] ?? ''; ?>">
                <span class="form__error"><?=$errors['step_bet'] ?? ''; ?></span>
            </div>
            <div class="form__item <?=isset($errors['date_end']) ? 'form__item--invalid' : ''; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="date_end" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=$form['date_end'] ?? ''; ?>">
                <span class="form__error"><?=$errors['date_end'] ?? ''; ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
