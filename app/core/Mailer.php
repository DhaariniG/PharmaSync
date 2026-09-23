<?php

// Sends order-confirmation emails through PHPMailer. If the library isn't
// present, sending just returns false so checkout still works.
class Mailer
{
    public static function available(): bool
    {
        // MAIL_ENABLED is false during development, so no page ever hangs
        // waiting for SMTP. Turn it on in config/config.php when real
        // credentials are in place.
        if (!MAIL_ENABLED) {
            return false;
        }

        return file_exists(__DIR__ . '/../../lib/PHPMailer/src/PHPMailer.php')
            && file_exists(__DIR__ . '/../../lib/PHPMailer/src/SMTP.php')
            && file_exists(__DIR__ . '/../../lib/PHPMailer/src/Exception.php');
    }

    public static function sendOrderConfirmation(array $user, array $order): bool
    {
        if (!self::available()) {
            return false;
        }

        require_once __DIR__ . '/../../lib/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../../lib/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../lib/PHPMailer/src/SMTP.php';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($user['email'], $user['name']);

            $mail->isHTML(true);
            $mail->Subject = 'PharmaSync — Order #' . $order['id'] . ' confirmed';
            $mail->Body    = self::buildHtml($user, $order);
            $mail->AltBody = self::buildText($user, $order);

            $mail->send();
            return true;
        } catch (\Throwable $e) {
            error_log('Mailer error: ' . $e->getMessage());
            return false;
        }
    }

    private static function buildHtml(array $user, array $order): string
    {
        $rows = '';
        foreach ($order['items'] as $item) {
            $rows .= '<tr><td>' . htmlspecialchars($item['name']) . '</td><td>' . $item['quantity'] . '</td><td>Rs. ' . number_format($item['unit_price'], 2) . '</td></tr>';
        }

        return "
            <h2>Thanks for your order, {$user['name']}!</h2>
            <p>Order #{$order['id']} has been placed successfully.</p>
            <table border='1' cellpadding='6' cellspacing='0'>
                <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
                {$rows}
            </table>
            <p><strong>Total: Rs. " . number_format($order['total'], 2) . "</strong></p>
            <p>We'll notify you when your order ships.</p>
        ";
    }

    private static function buildText(array $user, array $order): string
    {
        $lines = "Thanks for your order, {$user['name']}!\nOrder #{$order['id']}\n\n";
        foreach ($order['items'] as $item) {
            $lines .= "- {$item['name']} x{$item['quantity']} (Rs. " . number_format($item['unit_price'], 2) . ")\n";
        }
        $lines .= "\nTotal: Rs. " . number_format($order['total'], 2);
        return $lines;
    }

    public static function sendPasswordReset(string $email, string $name, string $resetUrl): bool
    {
        if (!self::available()) {
            error_log('Mailer error: sendPasswordReset skipped - MAIL_ENABLED is false or PHPMailer files are missing in lib/PHPMailer/src/');
            return false;
        }

        require_once __DIR__ . '/../../lib/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../../lib/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../lib/PHPMailer/src/SMTP.php';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;

            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'PharmaSync — Reset Your Password';
            $mail->Body    = "
                <h2>Password Reset Request</h2>
                <p>Hello " . htmlspecialchars($name) . ",</p>
                <p>We received a request to reset your password. Click the link below to set up a new password:</p>
                <p><a href='{$resetUrl}' style='padding: 10px 15px; background-color: #00bcd4; color: #fff; text-decoration: none; border-radius: 4px; display: inline-block;'>Reset Password</a></p>
                <p>This link expires in 1 hour. If you did not request this, please ignore this email.</p>
            ";
            $mail->AltBody = "Hello {$name},\n\nClick the link below to reset your password:\n{$resetUrl}\n\nThis link expires in 1 hour.";

            $mail->send();
            return true;
        } catch (\Throwable $e) {
            error_log('Mailer error: ' . $e->getMessage());
            return false;
        }
    }
}
