<?php

namespace App\Controller;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(): void
    {
        $this->render("home", "Accueil");
    }
}