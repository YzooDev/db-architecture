<?php

namespace App\Controller;

use App\Controller\AbstractController;

class HomeController extends AbstractController
{
    
    //Méthode pour gérer l'affichage de la page d'accueil
    public function index(): mixed 
    {
        return $this->render("home", "Accueil");
    }
}