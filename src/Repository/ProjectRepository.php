<?php

namespace App\Repository;

use App\Database\Mysql;
use App\Entity\Project;
use App\Entity\Image;

class ProjectRepository
{
    private \PDO $connect;

    public function __construct()
    {
        $this->connect = Mysql::connectBdd();
    }

    public function findAllProject(): array
    {
        // Création d'un tableau de projets 
        $projectsArray = [];
        try {
            //1 Ecrire la requête SQL
            $sql = 'SELECT p.id_project, p.project_name, p.project_description, p.project_location, p.project_year, p.category, p.built, p.created_at, 
            GROUP_CONCAT(i.filename) AS `filename`, GROUP_CONCAT(i.alt_text) AS `alt_text` 
            FROM `image` AS i 
            INNER JOIN project AS p ON p.id_project = i.project_id
            GROUP BY p.id_project;';
            //2 Préparer la requête
            $req = $this->connect->prepare($sql);
            
            //4 Exécuter la requête
            $req->execute();
            //5 Retourner la réponse (Tab asso)
            $projects = $req->fetchAll(\PDO::FETCH_ASSOC);
            //6 Parcours du FetchAll (FETCH_ASSOC)
            foreach ($projects as $project) {
                //Hydratation et ajout de la project au tableau
                $projectsArray[] = $this->hydrateProject($project);
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return $projectsArray;
    }

    public function addProject(Project $project): Project
    {
        try {
            $sql = "INSERT INTO project(project_name, project_description, project_location, project_year, category, built, created_at) VALUE(?, ?, ?, ?, ?, ?, ?);";

            $req = $this->connect->prepare($sql);

            $req->bindValue(1, $project->getName(), \PDO::PARAM_STR);
            $req->bindValue(2, $project->getDescription(), \PDO::PARAM_STR);
            $req->bindValue(3, $project->getLocation(), \PDO::PARAM_STR);
            $req->bindValue(4, $project->getYear(), \PDO::PARAM_INT);
            $req->bindValue(5, $project->getCategory(), \PDO::PARAM_STR);
            $req->bindValue(6, $project->getBuilt(), \PDO::PARAM_BOOL);
            $req->bindValue(7, $project->getCreatedAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);

            $req->execute();

            $id = $this->connect->lastInsertId();

            $project->setId($id);

        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return $project;
    }

    private function addImageToProject(Project $project): Project
    {
        try {
            foreach ($project->getImages() as $image) {
                $sqlAsso = "INSERT INTO `image`(project_id) VALUE(?) WHERE id_image = ?;";
                $reqAsso = $this->connect->prepare($sqlAsso);
                $reqAsso->bindValue(1, $project->getId(), \PDO::PARAM_INT);
                $reqAsso->bindValue(2, $image->getId(), \PDO::PARAM_INT);
                $reqAsso->execute();
            }
        } catch (\PDOException $e) {
        }
        return $project;
    }

    /**
     * Méthode pour convertir une row SQL (FETCH_ASSOC) en Entity Project
     * @param array $project Tableau associatif
     * @return Project Entity Project
     */
    private function hydrateProject(array $row): Project
    {
    
        //2 Création du projet
        $entityProject = new Project(
            $row["project_name"],
            $row["project_description"],
            $row["project_location"],
            $row["project_year"],
            $row["category"]
        );

        //3 Set la date de création
        $entityProject->setCreatedAt(new \DateTime($row["created_at"]));

        //4 Tableau des images
        $filename = explode(",",(string)$row["filename"]);
        $altText = explode(",",(string)$row["alt_text"]);

        //4 Boucle pour assigner les Images
        for ($i=0; $i <count($filename) ; $i++) {
            //5 Test si l'image existe
            if($altText[$i] != 0 && $filename[$i] != "") {
                //7 Création d'une nouvelle Image
                $img = new Image($filename[$i]);
                //8 Set de l'ID de la Category
                $img->setAltText((int)$altText[$i]);
                //9 Ajout de la Category à la collection de la Task
                $entityProject->setImage($img);
            }

        }

        return $entityProject;
    }
}