<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function sendContactMail(array $formData): void
    {
        $mail = new PHPMailer(true);

        $firstname = $formData['firstname'] ?? '';
        $lastname = $formData['lastname'] ?? '';
        $email = $formData['email'] ?? '';
        $description = $formData['description'] ?? '';

        $message = "Prénom : $firstname \nNom : $lastname \nEmail : $email \nDemande : $description";

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'remi.bezes.dev@gmail.com';
        $mail->Password   = 'icdi mskh futz naln';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->CharSet  = 'UTF-8';

        $mail->Encoding = 'base64';

        $mail->setFrom('noreply@db-architecture.fr', 'DB Architecture');
        $mail->addAddress('remi_bzes@hotmail.fr');

        $mail->isHTML(false); 
        $mail->Subject = "Nouveau message de $firstname $lastname";
        $mail->Body    = $message;

        $mail->send();
    }
}
