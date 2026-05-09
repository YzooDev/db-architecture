<?php

namespace App\Service;

use App\Entity\Project;
use App\Service\UploadService;
use App\Repository\ProjectRepository;
use App\Utils\Tools;

class ProjectService
{
    private ProjectRepository $projectRepository;
    private UploadService $uploadService;

    public function __construct()
    {
        $this->projectRepository = new ProjectRepository();
        $this->uploadService = new UploadService();
    }

    public function getAllProject(): array
    {
        return $this->projectRepository->findAllProjects();
    }

    public function getProjectById(int $id): ?Project
    {
        return $this->projectRepository->findProjectById($id);
    }

    public function insertProject(array $post, array $files = []): string
    {
        if (
            empty($post["name"]) ||
            empty($post["description"]) ||
            empty($post["location"]) ||
            empty($post["year"]) ||
            empty($post["category"])
        ) {
            return "Veuillez remplir les champs obligatoires.";
        }

        if (empty($files["images"]["name"][0])) {
            return "Veuillez sélectionner au moins une image.";
        }

        $existing = $this->projectRepository->findProjectByName($post["name"]);
        if ($existing !== null) {
            return "Un projet nommé \"" . htmlspecialchars($post["name"]) . "\" existe déjà.";
        }

        Tools::sanitize_array($post);

        $project = $this->mapFromPost($post);
        $this->projectRepository->saveProject($project);

        $uploadErrors = $this->uploadService->storeImages($files['images'], $project->getId());

        $message = "Le projet \"" . $project->getName() . "\" a été créé avec succès.";
        if (!empty($uploadErrors)) {
            $message .= " Erreurs images : " . implode(', ', $uploadErrors);
        }

        return $message;
    }

    public function modifyProject(int $id, array $post): string
    {
        if (
            empty($post["name"]) ||
            empty($post["description"]) ||
            empty($post["location"]) ||
            empty($post["year"]) ||
            empty($post["category"])
        ) {
            return "Veuillez remplir les champs obligatoires.";
        }

        $project = $this->projectRepository->findProjectById($id);
        if (!$project) {
            return "Projet introuvable.";
        }

        Tools::sanitize_array($post);

        $project->setName($post["name"]);
        $project->setDescription($post["description"]);
        $project->setLocation($post["location"]);
        $project->setYear((int) $post["year"]);
        $project->setCategory($post["category"]);
        $project->setBuilt(!empty($post["built"]));

        $this->projectRepository->updateProject($project);

        return "Le projet \"" . $project->getName() . "\" a été mis à jour.";
    }

    public function deleteProject(int $id): void
    {
        $project = $this->projectRepository->findProjectById($id);

        if ($project !== null) {
            $this->uploadService->deleteAllProjectFiles($project->getImages());
        }

        $this->projectRepository->destroyProject($id);
    }

    private function mapFromPost(array $post): Project
    {
        $project = new Project(
            $post["name"],
            $post["description"],
            $post["location"],
            (int) $post["year"],
            $post["category"]
        );

        if (!empty($post["built"])) {
            $project->setBuilt(true);
        }

        return $project;
    }
}
