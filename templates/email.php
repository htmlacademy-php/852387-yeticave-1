<?php

declare(strict_types=1);

/**
 * @var int $lot_id ID лота
 * @var string $lot_name название лота
 * @var string $user_name имя пользователя (получателя письма)
 **/
?>

<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $user_name ?></p>
<p>Ваша ставка для лота <a href="<?= create_new_url('lot.php', ['id' => $lot_id]) ?>"><?= $lot_name ?></a> победила.
</p>
<p>Перейдите по ссылке <a href="<?= create_new_url('my-bets.php') ?>">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет-Аукцион "YetiCave"</small>
