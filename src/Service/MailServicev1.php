<?php 

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use App\Service\Exception\UploadException;

class MailService
{
    public function sendContactMail(array $formData): void
    {
        $mail = new PHPMailer(true);

        // Configuration SMTP (exemple avec Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ton.email@gmail.com';
        $mail->Password   = 'ton_mot_de_passe_application';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Expéditeur et destinataire
        $mail->setFrom($formData['email'], $formData['name']);
        $mail->addAddress('ton.email@gmail.com');

        // Contenu
        $mail->Subject = 'Nouveau message de contact';
        $mail->Body    = $formData['message'];

        $mail->send();
    }
}