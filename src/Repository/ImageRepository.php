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

    // public function findAllCovers(): array
    // {
    //     try {
    //         $sql = 'SELECT i.id_image, i.filename FROM `image` as i
    //         WHERE i.is_cover = 1;';
    //         $req = $this->connect->prepare($sql);
    //         $req->execute();
    //         $images = $req->fetchAll(\PDO::FETCH_ASSOC);
    //         $imagesArray = [];
    //         foreach ($images as $image) {
    //             $imagesArray[] = $this->hydrateImage($image);
    //         }
    //     } catch (\PDOException $e) {
    //     }
    //     return $imagesArray;
    // }

    public function addImage(Image $image): Image
    {
        try {
        $sql = "INSERT INTO `image`(`filename`, alt_text, is_cover, sort_order, uploaded_at, project_id) VALUES(?, ?, ?, ?, ?, ?)";

        $req = $this->connect->prepare($sql);

        $req->bindValue(1, $image->getFilename(), \PDO::PARAM_STR);
        $req->bindValue(2, $image->getAltText(), \PDO::PARAM_STR);
        $req->bindValue(3, $image->getIsCover(), \PDO::PARAM_BOOL);
        $req->bindValue(1, $image->getSortOrder(), \PDO::PARAM_INT);
        $req->bindValue(1, $image->getUploadedAt()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $req->bindValue(1, $image->getProjectId(), \PDO::PARAM_INT);

        $req->execute();

        $id = $this->connect->lastInsertId();

        $image->setId($id);
        
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return $image;
    }

    private function hydrateImage(array $row): Image 
    {
        $entityImage = new Image(
            $row["filename"]
        );

        $entityImage
            ->setId($row["id_image"]);

        return $entityImage;
    }
}