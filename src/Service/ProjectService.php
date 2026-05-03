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
        $this->projectRepository = new ProjectRepository;
        $this->uploadService = new UploadService;
    }

    public function getAllProject()
    {
        return $this->projectRepository->findAllProject();
    }

    public function insertProject(array $project, array $files = []): string
    {
        //1 Vérifier si les champs sont vides
        if (
            empty($project["name"]) ||
            empty($project["description"]) ||
            empty($project["location"]) ||
            empty($project["year"]) ||
            empty($project["category"])
        ) {
            return "Veuillez remplir les champs obligatoires";
        }

        //2 Vérifier si il y a au moins une image
        if (
            empty($files["images"]["name"][0])
        ) { 
            return "Veuillez sélectionner au moins une image";
        }

        //3 Nettoyer les entrées utilisateurs
        Tools::sanitize_array($project);

        //3 Mapper le tableau (Super globale POST)
        $newProject = $this->mapFromPost($project);
        
        //4 Ajout en BDD du projet et des images associées
        $this->projectRepository->addProject($newProject);

        $uploadErrors = $this->uploadService->uploadMultiple($files['images'], $newProject->getId());


        return "Le projet : " . $newProject->getName() . " a été ajouté en BDD";
    }

        /**
     * Méthode pour convertir la super globale POST (formulaire) en Entity Project
     * @param array $project Super Globale POST
     * @return Project Entity Project
     */
    private function mapFromPost(array $project): Project 
    {
        //1 Créer un objet Project
        $newProject = new Project(
            $project["name"], 
            $project["description"], 
            $project["location"], 
            $project["year"], 
            $project["category"]
        );
        
        //2 Set si la valeur est non vide
        if(!empty($project["created_at"])) {
            $newProject->setCreatedAt(new \DateTime($project["created_at"]));
        }
        if (!empty($project["built"])) {
            $newProject->setBuilt(true);
        }

        return $newProject;
    }

}