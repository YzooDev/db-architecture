<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\ProjectService;

class ProjectController extends AbstractController
{
    private ProjectService $projectService;

    public function showAllProject(): mixed 
    {
        return $this->render("project", "Projets");
    }

    public function manageProject(): mixed
    {
        return $this->render("manage_project", "Gérer les projets");
    }

    public function createProject(): mixed
    {
        // return $this->render("add_project", "Ajouter un projet", $data);
        return $this->render("add_project", "Ajouter un projet");
    }
}