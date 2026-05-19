<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $name;
    public $token;
    public function __construct($email, $name, $token) {

        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function sendConfirmation () {

        // Create the email object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('accounts@appsalon.com', 'AppSalon.com');
        $mail->addAddress($this->email, $this->name);
        $mail->Subject = 'Confirm your account';

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = "<html>";
        $content .= "<p><strong>Hello " . $this->name . " </strong>You have created your account, you just need to confirm it by clicking the following link</p>";
        $content .= "<p>Click here: <a href='". $_ENV['APP_URL'] . "/confirm-account?token=" . $this->token . "'>Confirm Account</a> </p>";
        $content .= "<p>If you did not request this account, you can ignore this message</p>";
        $content .= "</html>";

        $mail->Body = $content;

        // Send Email
        $mail->send();
    }

    public function sendInstructions () {
        // Create the email object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('accounts@appsalon.com', 'AppSalon.com');
        $mail->addAddress($this->email, $this->name);
        $mail->Subject = 'Reset your password';

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = "<html>";
        $content .= "<p><strong>Hello " . $this->name . " </strong>You have requested to reset your password, please follow the link below to do so.</p>";
        $content .= "<p>Click here: <a href='". $_ENV['APP_URL'] . "/recover?token=" . $this->token . "'>Reset Password</a> </p>";
        $content .= "<p>If you did not request this account, you can ignore this message</p>";
        $content .= "</html>";

        $mail->Body = $content;

        // Send Email
        $mail->send();
    }
}