<?php
declare(strict_types=1);

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

require_once('vendor/autoload.php');

/**
 * Отправляет письмо пользователю
 * @param string $email электронная почта пользователя
 * @param string $user_name имя пользователя
 * @param int $lot_id ID лота, по выигрышной ставке пользователя
 * @param string $lot_name название лота
 *
 * @throws TransportExceptionInterface
 */
function sent_mail(string $email, string $user_name, int $lot_id, string $lot_name): void
{
    $dsn = 'smtp://lesmir-15@mail.ru:cMl4ZvwW4lFya5jzeQzd@smtp.mail.ru:465?encryption=ssl&auth_mode=login';
    $transport = Transport::fromDsn($dsn);

    $mailer = new Mailer($transport);

    $message = new Email();
    $message->subject('Ваша ставка победила');
    $message->from('lesmir-15@mail.ru');
    $message->to('lesmir-15@mail.ru'); // $email из БД, для проверки свой 'lesmir-15@mail.ru'

    $msg_content = include_template('email.php', [
        'lot_id' => $lot_id,
        'lot_name' => $lot_name,
        'user_name' => $user_name
    ]);
    $message->html($msg_content);

    $mailer->send($message);
}
