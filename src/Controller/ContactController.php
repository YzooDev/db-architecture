<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\MailService;

class ContactController extends AbstractController
{
    private MailService $mailService;

    public function __construct()
    {
        $this->mailService = new MailService;
    }

    public function handle()
    {
        $errors  = [];
        $formData = [];

        if (isset($_POST["submit"])) {
            $formData = [
                'firstname' => trim($_POST['firstname'] ?? ''),
                'lastname' => trim($_POST['lastname'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
            ];

            if (empty($formData['firstname'])) {
                $errors['firstname'] = 'Le prénom est requis.';
            }
            if (empty($formData['lastname'])) {
                $errors['lastname'] = 'Le nom est requis.';
            }
            if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email invalide.';
            }
            if (empty($formData['description'])) {
                $errors['description'] = 'Le message est requis.';
            }

            if (empty($errors)) {
                $mailer = new MailService();
                $mailer->sendContactMail($formData);
                header('Location: /contact?success=1');
                exit;
            }
        }

        $data = compact('errors', 'formData');
        return $this->render("contact", "Contact", $data);
    }
}