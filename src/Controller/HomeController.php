<?php

namespace App\Controller;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(): void
    {
        $this->render("home", "Accueil");
    }

    public function legal(): void
    {
        $this->render("terms", "Mentions légales & CGU");
    }
}