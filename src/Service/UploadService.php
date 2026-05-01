<?php

namespace App\Service;

use App\Utils\Tools;
use App\Service\Exception\UploadException;
use App\Entity\Image;

class UploadService
{
    /**
     * Attributs du service d'upload
     */
    private readonly string $uploadtarget;
    private readonly int $uploadSizeMax;
    private readonly string $uploadFormatWhiteList;
    private array $uploadFormats;

    public function __construct()
    {
        //Initialisation des attributs (depuis le fichier .env)
        $this->uploadtarget = rtrim($_ENV["UPLOAD_DIRECTORY"], "/\\") . DIRECTORY_SEPARATOR;
        $this->uploadSizeMax = (int) $_ENV["UPLOAD_SIZE_MAX"];
        $this->uploadFormatWhiteList = $_ENV["UPLOAD_FORMAT_WHITE_LIST"];
        $this->uploadFormats = json_decode($this->uploadFormatWhiteList, true) ?? [];
    }
    
    /**
     * Méthode pour uploader un fichier
     * @param array $files (super globale Files)
     * @param string $name (nom du fichier)
     * @return string Nom de l'image uploadée
     * @throws UploadException
     */
    public function uploadFile(array $files): string
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

        $addImage = $this->mapFromFile($files);
        return "L'image : " . $addImage->getFilename() . " a été ajouté en BDD";
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

        /**
     * Méthode pour convertir la super globale FILES (formulaire) en Entity Image
     * @param array $image Super Globale FILES
     * @return Image Entity Image
     */
    private function mapFromFile(array $image): Image
    {
        //1 Créer un objet Image
        $addImage = new Image($image["name"]);

        //5 Set si la valeur est non vide
        if(!empty($image["created_at"])) {
            $addImage->setUploadedAt(new \DateTime($image["uploaded_at"]));
        }

        return $addImage;
    }
}