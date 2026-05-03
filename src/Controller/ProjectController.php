<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\ProjectService;

class ProjectController extends AbstractController
{
    private ProjectService $projectService;
    
    public function __construct()
    {
        $this->projectService = new ProjectService();
    }

    public function showAllProject()
    {
        //Récupération de la liste des ptojets
        $projects = $this->projectService->getAllProject();

        //rendu du template
        return $this->render("project", "Projets", $projects);
    }

    public function createProject()
    {
        if (!isset($_SESSION["connected"])) header('Location:/');

        $data = [];

        if(isset($_POST["submit"])) {
            $data["msg"] = $this->projectService->insertProject($_POST, $_FILES);
        }

        return $this->render("add_project","Ajouter un projet", $data);
    }

    public function adminProject()
    {
        if (!isset($_SESSION["connected"])) header('Location:/');

        $projects = $this->projectService->getAllProject();

        return $this->render("admin_project", "Gérer les Projets", $projects);
    }
}