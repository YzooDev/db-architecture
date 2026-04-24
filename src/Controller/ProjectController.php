<?php

namespace App\Controller;

use App\Controller\AbstractController;

class ProjectController extends AbstractController
{
    public function index(): mixed 
    {
        return $this->render("project", "Projets");
    }
}