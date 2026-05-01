<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Image;
use App\Service\UploadService;
use App\Repository\ImageRepository;
use App\Repository\ProjectRepository;
use App\Utils\Tools;

class ProjectService 
{
    private ProjectRepository $projectRepository;
    private ImageRepository $imageRepository;

    public function __construct()
    {
        $this->projectRepository = new ProjectRepository;
        $this->imageRepository = new ImageRepository;
    }

    public function getAllProject()
    {
        return $this->projectRepository->findAllProject();
    }

    public function insertProject(array $project): string
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

        // if (
        //     empty($project["image"])
        // ) { 
        //     return "Veuillez télécharger au moins une image";
        // }
        //2 Nettoyer les entrées utilisateurs
        Tools::sanitize_array($project);

        //3 Mapper le tableau (Super globale POST)
        $addProject = $this->mapFromPost($project);
        
        //4 Ajout en BDD du projet et des images associées
        $this->projectRepository->addProject($addProject);

        return "Le projet : " . $addProject->getName() . " a été ajouté en BDD";
    }

        /**
     * Méthode pour convertir la super globale POST (formulaire) en Entity Project
     * @param array $project Super Globale POST
     * @return Project Entity Project
     */
    private function mapFromPost(array $project): Project 
    {
        //1 Créer un objet Project
        $addProject = new Project($project["name"], $project["description"], $project["location"], $project["year"], $project["category"]);
        //2 Ajouter les images
        // foreach ($project["image"] as $value) {
        //     //3 Créer une image
        //     $image = new Image();
        //     $image->setId($value);
        //     //4 Ajouter les images au projet
        //     $addProject->setImage($image);
        // }
        //5 Set si la valeur est non vide
        if(!empty($project["created_at"])) {
            $addProject->setCreatedAt(new \DateTime($project["created_at"]));
        }

        return $addProject;
    }

}