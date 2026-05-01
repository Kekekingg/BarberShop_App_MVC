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
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '1aec537547bfb6';
        $mail->Password = '2e759e6ba294d8';

        $mail->setFrom('accounts@appsalon.com');
        $mail->addAddress('accounts@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirm your account';

        // Set HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = "<html>";
        $content .= "<p><strong>Hello " . $this->email . " </strong>You have created your account, you just need to confirm it by clicking the following link</p>";
        $content .= "<p>Click here: <a href='http://localhost:3000/confirm-account?token=" . $this->token . "'>Confirm Account</a> </p>";
        $content .= "<p>If you did not request this account, you can ignore this message</p>";
        $content .= "</html>";

        $mail->Body = $content;

        // Send Email
        $mail->send();
    }
}