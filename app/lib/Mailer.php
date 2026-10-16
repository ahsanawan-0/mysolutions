<?php
declare(strict_types=1);

namespace App;

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Outgoing email (PROMPTS P23, docs/13 §8): PHPMailer over authenticated SMTP (TLS) with the credentials in .env, or
 * MAIL_DRIVER=log on local, which writes each message as an .eml file to storage/mail/ instead of sending it.
 * Failures are logged to storage/logs/mail.log WITHOUT the message body or the sender's details (only the kind of
 * message, the recipient's domain and PHPMailer's error).
 *
 *   Mailer::send(['to' => …, 'subject' => …, 'html' => …, 'text' => …, 'reply_to' => [email, name]?, 'kind' => 'enquiry'])
 *     → true when sent (or written by the log driver)
 */
final class Mailer
{
    public static function send(array $message): bool
    {
        $kind = (string) ($message['kind'] ?? 'message');
        try {
            $mail = new PHPMailer(true);
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Encoding = PHPMailer::ENCODING_QUOTED_PRINTABLE;
            $mail->setFrom((string) env('MAIL_FROM_ADDRESS', config('email')), (string) env('MAIL_FROM_NAME', config('name')));
            $mail->addAddress((string) $message['to'], (string) ($message['to_name'] ?? ''));
            if (!empty($message['reply_to'])) {
                [$replyEmail, $replyName] = array_pad((array) $message['reply_to'], 2, '');
                $mail->addReplyTo((string) $replyEmail, (string) $replyName);
            }
            $mail->Subject = (string) $message['subject'];
            $mail->isHTML(true);
            $mail->Body = (string) $message['html'];
            $mail->AltBody = (string) $message['text'];

            if (env('MAIL_DRIVER', 'smtp') === 'log') {
                $mail->preSend();
                return self::writeEml($kind, $mail->getSentMIMEMessage());
            }

            $mail->isSMTP();
            $mail->Host = (string) env('SMTP_HOST', '');
            $mail->Port = (int) env('SMTP_PORT', '587');
            $mail->SMTPAuth = env('SMTP_USERNAME', '') !== '';
            $mail->Username = (string) env('SMTP_USERNAME', '');
            $mail->Password = (string) env('SMTP_PASSWORD', '');
            $mail->SMTPSecure = env('SMTP_ENCRYPTION', 'tls') === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Timeout = 15;
            return $mail->send();
        } catch (MailException $e) {
            self::log($kind, (string) ($message['to'] ?? ''), $e->getMessage());
            return false;
        }
    }

    private static function writeEml(string $kind, string $mime): bool
    {
        $dir = storage_path('mail');
        if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
            self::log($kind, '', 'storage/mail is not writable');
            return false;
        }
        $file = sprintf('%s/%s-%s-%s.eml', $dir, date('Ymd-His'), preg_replace('/[^a-z0-9-]/', '', $kind), bin2hex(random_bytes(3)));
        if (file_put_contents($file, $mime) === false) {
            self::log($kind, '', 'could not write ' . basename($file));
            return false;
        }
        return true;
    }

    /** One line per failure: time, kind, recipient domain, error. Never the body or the sender's details. */
    private static function log(string $kind, string $to, string $error): void
    {
        $domain = str_contains($to, '@') ? substr($to, (int) strrpos($to, '@') + 1) : '-';
        $line = sprintf("[%s] %s to *@%s failed: %s\n", date('c'), $kind, $domain, preg_replace('/\s+/', ' ', $error));
        @file_put_contents(storage_path('logs/mail.log'), $line, FILE_APPEND | LOCK_EX);
    }
}
