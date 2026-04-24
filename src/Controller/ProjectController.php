<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\CategoryService;
use App\Service\ProjectService;

class ProjectController extends AbstractController
{
    private ProjectService $projectService;

    public function index(): mixed 
    {
        return $this->render("project", "Projets");
    }
}