<?php

namespace App\Repository;

use App\Database\Mysql;
use App\Entity\Image;

class ImageRepository
{
    private \PDO $connect;

    public function __construct()
    {
        $this->connect = Mysql::connectBdd();
    }

    public function saveImage(Image $image): Image
    {
        try {
            $sql = "INSERT INTO `image`(`filename`, alt_text, is_cover, sort_order, uploaded_at, project_id)
                VALUES(?, ?, ?, ?, ?, ?)";
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $image->getFilename(), \PDO::PARAM_STR);
            $req->bindValue(2, $image->getAltText(), \PDO::PARAM_STR);
            $req->bindValue(3, $image->getIsCover() ? 1 : 0, \PDO::PARAM_INT);
            $req->bindValue(4, $image->getSortOrder(), \PDO::PARAM_INT);
            $req->bindValue(5, $image->getUploadedAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $req->bindValue(6, $image->getProjectId(), \PDO::PARAM_INT);
            $req->execute();
            $image->setId((int) $this->connect->lastInsertId());
        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur insertion image : " . $e->getMessage());
        }
        return $image;
    }

    public function findImageById(int $id): ?Image
    {
        try {
            $sql = "SELECT i.id_image, i.`filename`, i.alt_text, i.is_cover, i.sort_order, i.uploaded_at, i.project_id 
                FROM `image` i WHERE id_image = ?";
            $req = $this->connect->prepare($sql);
            $req->bindValue(1, $id, \PDO::PARAM_INT);
            $req->execute();
            $row = $req->fetch(\PDO::FETCH_ASSOC);
            return $row ? $this->hydrateImage($row) : null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function clearCoverByProject(int $projectId): void
    {
        $sql = "UPDATE `image` SET is_cover = 0 WHERE project_id = ?";
        $req = $this->connect->prepare($sql);
        $req->bindValue(1, $projectId, \PDO::PARAM_INT);
        $req->execute();
    }

    public function setCoverImage(int $imageId): void
    {
        $sql = "UPDATE `image` SET is_cover = 1 WHERE id_image = ?";
        $req = $this->connect->prepare($sql);
        $req->bindValue(1, $imageId, \PDO::PARAM_INT);
        $req->execute();
    }

    public function setFirstImageAsCover(int $projectId): void
    {
        $sql = "UPDATE `image` SET is_cover = 1 WHERE project_id = ? ORDER BY sort_order ASC LIMIT 1";
        $req = $this->connect->prepare($sql);
        $req->bindValue(1, $projectId, \PDO::PARAM_INT);
        $req->execute();
    }

    public function destroyImage(int $imageId): void
    {
        $sql = "DELETE FROM `image` WHERE id_image = ?";
        $req = $this->connect->prepare($sql);
        $req->bindValue(1, $imageId, \PDO::PARAM_INT);
        $req->execute();
    }

    private function hydrateImage(array $row): Image
    {
        $image = new Image(
            $row['filename'],
            (bool) $row['is_cover'],
            (int) $row['sort_order'],
            (int) $row['project_id']
        );
        $image->setId((int) $row['id_image']);
        $image->setAltText($row['alt_text'] ?? '');
        if (!empty($row['uploaded_at'])) {
            $image->setUploadedAt(new \DateTime($row['uploaded_at']));
        }
        return $image;
    }
}
