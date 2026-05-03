<?php

namespace App\Service;

use App\Utils\Tools;
use App\Service\Exception\UploadException;
use App\Entity\Image;
use App\Repository\ImageRepository;

class UploadService
{
    /**
     * Attributs du service d'upload
     */
    private readonly string $uploadtarget;
    private readonly int $uploadSizeMax;
    private readonly string $uploadFormatWhiteList;
    private array $uploadFormats;
    private ImageRepository $imageRepository;

    public function __construct()
    {
        //Initialisation des attributs (depuis le fichier .env)
        $this->uploadtarget = rtrim($_ENV["UPLOAD_DIRECTORY"], "/\\") . DIRECTORY_SEPARATOR;
        $this->uploadSizeMax = (int) $_ENV["UPLOAD_SIZE_MAX"];
        $this->uploadFormatWhiteList = $_ENV["UPLOAD_FORMAT_WHITE_LIST"];
        $this->uploadFormats = json_decode($this->uploadFormatWhiteList, true) ?? [];
        $this->imageRepository       = new ImageRepository();
    }
    
    /**
     * Méthode pour uploader un fichier
     * @param array $files (super globale Files)
     * @param string $name (nom du fichier)
     * @return string Nom de l'image uploadée
     * @throws UploadException
     */
    public function uploadFile(array $files, int $projectId, bool $isCover = false, int $sortOrder = 0): string
    {
        //Test si le fichier est incorrectement uplodé
        if ($this->isFileNotUploadCorrectly($files)) {
            throw new UploadException("Pas de fichier à importer");
        }
        if (!isset($files["error"]) || $files["error"] !== UPLOAD_ERR_OK) {
            throw new UploadException("Erreur lors de l'upload du fichier");
        }
        if (!is_uploaded_file($files["tmp_name"])) {
            throw new UploadException("Fichier uploadé invalide");
        }

        //test de la taille
        if ($this->validateUploadSize($files)) {
            throw new UploadException("La taille du fichier est trop importante");
        }

        //Récupération de l'extension
        $ext = Tools::getFileExtension($files["name"]);

        //Test si le format du fichier est valide
        if (!$this->validateUploadFormat($ext)) {
            throw new UploadException("Le format " . $ext . " est invalide");
        }
        
        //rename files
        $newName =  $this->renameFile($ext);
        $uploadTmp = $files["tmp_name"];
        $uploadtarget = $this->uploadtarget . $newName;

        //move to Upload_directory
        if (!is_dir($this->uploadtarget) || !is_writable($this->uploadtarget)) {
            throw new UploadException("Dossier d'upload introuvable ou non inscriptible");
        }
        if (!move_uploaded_file($uploadTmp, $uploadtarget)) {
            throw new UploadException("Échec lors du déplacement du fichier");
        }

        Tools::sanitize_array($files);

        $image = new Image($newName, $isCover, $sortOrder, $projectId);
        $image->setAltText(pathinfo($files["name"], PATHINFO_FILENAME));
        $this->imageRepository->addImage($image);
        return $newName;

        return "L'image :  a été ajouté en BDD";
    }

    /**
     * Méthode pour tester si l'image à bien été uploadée
     * @param array $files (données du fichier)
     * @return bool Vrai si le fichier a été uploadé incorrectement, faux sinon
     */
    private function isFileNotUploadCorrectly(array $files): bool
    {
        return !isset($files["tmp_name"]) || empty($files["tmp_name"]);
    }

    /**
     * Méthode pour valider la taille de l'upload
     * @param array $files (données du fichier)
     * @return bool Vrai si la taille est trop importante, faux sinon
     */
    private function validateUploadSize(array $files): bool
    {
        return $files["size"] > $this->uploadSizeMax;
    }

    /**
     * Méthode pour valider le format de l'upload
     * @param string $ext (extension du fichier)
     * @return bool Vrai si le format est valide, faux sinon
     */
    private function validateUploadFormat(string $ext): bool
    {
        if (empty($this->uploadFormats)) {
            return false;
        }
        return in_array($ext, $this->uploadFormats, true);
    }

   

    /**
     * Méthode pour renommer le fichier
     * @param string $ext (extension du fichier)
     * @return string Nouveau nom du fichier
     */
    private function renameFile(string $ext): string
    {
        return uniqid("image_") . "." . $ext;
    }

    public function uploadMultiple(array $images, int $projectId): array
    {
        $uploadErrors = [];

        foreach (array_keys($images["name"]) as $value) {
            if ($images["error"][$value] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $newImage = [
                "name" => $images["name"][$value],
                "type" => $images["type"][$value],
                "tmp_name" => $images["tmp_name"][$value],
                "error" => $images["error"][$value],
                "size" => $images["size"][$value]
            ];

            if ($value === 0) {
                $isCover = true;
            } else {
                $isCover = false;
            }

            try {
                $this->uploadFile($newImage, $projectId, $isCover, $value);
            } catch (UploadException $e) {
                $uploadErrors[] = '"' . $newImage["name"][$value] . '" : ' . $e->getMessage();
            }
        }
        return $uploadErrors;
    }
}