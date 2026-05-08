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

    public function findAllProjects(): array
    {
        $projectsArray = [];
        try {
            $sql = 'SELECT p.id_project, p.project_name, p.project_description,
                           p.project_location, p.project_year, p.category,
                           p.built, p.created_at
                    FROM project AS p
                    ORDER BY p.created_at DESC';

            $req = $this->connect->prepare($sql);
            $req->execute();

            foreach ($req->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $project = $this->hydrateProject($row);
                $projectsArray[] = $this->loadImages($project);
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return $projectsArray;
    }

    public function findProjectById(int $id): ?Project
    {
        try {
            $sql = 'SELECT p.id_project, p.project_name, p.project_description,
                           p.project_location, p.project_year, p.category,
                           p.built, p.created_at
                    FROM project AS p WHERE p.id_project = ?';

            $req = $this->connect->prepare($sql);
            $req->execute([$id]);
            $row = $req->fetch(\PDO::FETCH_ASSOC);

            if (!$row) return null;

            return $this->loadImages($this->hydrateProject($row));

        } catch (\PDOException $e) {
            return null;
        }
    }

    public function findProjectByName(string $name): ?Project
    {
        try {
            $sql = "SELECT * FROM project
                    WHERE LOWER(TRIM(project_name)) = LOWER(TRIM(?))
                    LIMIT 1";
            $req = $this->connect->prepare($sql);
            $req->execute([$name]);
            $row = $req->fetch(\PDO::FETCH_ASSOC);
            return $row ? $this->hydrateProject($row) : null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function saveProject(Project $project): Project
    {
        try {
            $sql = "INSERT INTO project(project_name, project_description, project_location,
                                        project_year, category, built, created_at)
                    VALUES(?, ?, ?, ?, ?, ?, ?)";

            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $project->getName(),                              \PDO::PARAM_STR);
            $req->bindValue(2, $project->getDescription(),                       \PDO::PARAM_STR);
            $req->bindValue(3, $project->getLocation(),                          \PDO::PARAM_STR);
            $req->bindValue(4, $project->getYear(),                              \PDO::PARAM_INT);
            $req->bindValue(5, $project->getCategory(),                          \PDO::PARAM_STR);
            $req->bindValue(6, $project->getBuilt() ? 1 : 0,                    \PDO::PARAM_INT);
            $req->bindValue(7, $project->getCreatedAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $req->execute();

            $project->setId((int) $this->connect->lastInsertId());
        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur création projet : " . $e->getMessage());
        }
        return $project;
    }

    public function updateProject(Project $project): void
    {
        try {
            $sql = "UPDATE project SET
                        project_name        = ?,
                        project_description = ?,
                        project_location    = ?,
                        project_year        = ?,
                        category            = ?,
                        built               = ?
                    WHERE id_project = ?";

            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $project->getName(),           \PDO::PARAM_STR);
            $req->bindValue(2, $project->getDescription(),    \PDO::PARAM_STR);
            $req->bindValue(3, $project->getLocation(),       \PDO::PARAM_STR);
            $req->bindValue(4, $project->getYear(),           \PDO::PARAM_INT);
            $req->bindValue(5, $project->getCategory(),       \PDO::PARAM_STR);
            $req->bindValue(6, $project->getBuilt() ? 1 : 0, \PDO::PARAM_INT);
            $req->bindValue(7, $project->getId(),             \PDO::PARAM_INT);
            $req->execute();
        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur mise à jour projet : " . $e->getMessage());
        }
    }

    public function destroyProject(int $id): void
    {
        try {
            $req = $this->connect->prepare("DELETE FROM project WHERE id_project = ?");
            $req->execute([$id]);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur suppression projet : " . $e->getMessage());
        }
    }

    private function loadImages(Project $project): Project
    {
        $sql = 'SELECT * FROM `image` WHERE project_id = ? ORDER BY sort_order ASC';
        $req = $this->connect->prepare($sql);
        $req->execute([$project->getId()]);

        foreach ($req->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $image = new Image(
                $row['filename'],
                (bool) $row['is_cover'],
                (int)  $row['sort_order'],
                (int)  $row['project_id']
            );
            $image->setId((int) $row['id_image']);
            $image->setAltText($row['alt_text'] ?? '');
            $image->setUploadedAt(new \DateTime($row['uploaded_at']));
            $project->setImage($image);
        }
        return $project;
    }

    private function hydrateProject(array $row): Project
    {
        $project = new Project(
            $row["project_name"],
            $row["project_description"],
            $row["project_location"],
            (int) $row["project_year"],
            $row["category"]
        );
        $project->setId((int) $row["id_project"]);
        $project->setCreatedAt(new \DateTime($row["created_at"]));
        $project->setBuilt((bool) $row["built"]);
        return $project;
    }
}
