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
        $mail->Password = '****94d8';

        $mail->setFrom('accounts@appsalon.com');
        $mail->addAddress('accounts@appsalon.com', 'AppSalon.com');
    }
}         