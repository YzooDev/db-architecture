<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\ProjectService;
use App\Service\UploadService;

class ProjectController extends AbstractController
{
    private ProjectService $projectService;
    private UploadService  $uploadService;

    public function __construct()
    {
        $this->projectService = new ProjectService();
        $this->uploadService  = new UploadService();
    }

    public function showAllProject(): void
    {
        $projects = $this->projectService->getAllProject();
        $this->render("project", "Projets", ['projects' => $projects]);
    }

    public function showProject(int $id): void
    {
        $project = $this->projectService->getProjectById($id);

        if (!$project) {
            http_response_code(404);
            echo "404 — Projet introuvable";
            return;
        }

        $this->render("project_detail", $project->getName(), ['project' => $project]);
    }

    public function listProjects(): void
    {
        $this->isConnected();
        $projects = $this->projectService->getAllProject();
        $this->render("admin_project", "Gérer les Projets", ['projects' => $projects]);
    }

    public function createProject(): void
    {
        $this->isConnected();
        $data = [];

        if (isset($_POST["submit"])) {
            $data["msg"] = $this->projectService->insertProject($_POST, $_FILES);
        }

        $this->render("add_project", "Ajouter un projet", $data);
    }


    public function editProject(int $id): void
{
    $this->isConnected();

    $project = $this->projectService->getProjectById($id);

    if (!$project) {
        $this->redirect('/admin/project');
    }

    $msg = null;

    if (isset($_POST["submit_edit"])) {
        $msg = $this->projectService->modifyProject($id, $_POST);
        $project = $this->projectService->getProjectById($id);
    }

    $this->render("admin_project_edit", "Modifier : " . $project->getName(), [
        'project' => $project,
        'msg'     => $msg,
    ]);
}

    public function removeProject(int $id): void
    {
        $this->isConnected();
        
        if (isset($_POST["submit_delete"])) {
            $this->projectService->deleteProject($id);
        }

        $this->redirect('/admin/project');
    }

    public function storeImages(int $projectId): void
    {
        $this->isConnected();
        $project = $this->projectService->getProjectById($projectId);
        $existCount = $project ? count($project->getImages()) : 0;

        $this->uploadService->storeImages(
            $_FILES['images'] ?? [],
            $projectId,
            $existCount
        );

        $this->redirect('/admin/project/' . $projectId . '/edit');
    }

    public function assignCoverImage(int $projectId, int $imageId): void
    {
        $this->isConnected();
        
        if (isset($_POST["submit_cover"])) {
            $this->uploadService->defineCoverImage($imageId, $projectId);
        }

        $this->redirect('/admin/project/' . $projectId . '/edit');
    }

    public function removeImage(int $projectId, int $imageId): void
    {
        $this->isConnected();
        
        if (isset($_POST["submit_delete_image"])) {
            $this->uploadService->deleteImageFile($imageId, $projectId);
        }

        $this->redirect('/admin/project/' . $projectId . '/edit');
    }
}
