<?php

namespace App\Controller;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    
    //Méthode pour gérer l'affichage de la page d'accueil
    public function index(): void
    {
        $this->render("home", "Accueil");
    }
}