<?php

namespace App\Service;

use App\Utils\Tools;
use App\Service\Exception\UploadException;
use App\Entity\Image;
use App\Repository\ImageRepository;

class UploadService
{
    private readonly string $uploadtarget;
    private readonly int    $uploadSizeMax;
    private array           $uploadFormats;
    private ImageRepository $imageRepository;

    public function __construct()
    {
        $this->uploadtarget    = rtrim($_ENV["UPLOAD_DIRECTORY"], "/\\") . DIRECTORY_SEPARATOR;
        $this->uploadSizeMax   = (int) $_ENV["UPLOAD_SIZE_MAX"];
        $this->uploadFormats   = json_decode($_ENV["UPLOAD_FORMAT_WHITE_LIST"], true) ?? [];
        $this->imageRepository = new ImageRepository();
    }

    // Méthodes publiques (appelées par ProjectController)

    /**
     * Uploade plusieurs images et les rattache à un projet.
     * Appelée par ProjectController::storeImages().
     *
     * @param array $rawFiles   $_FILES['images'] tel que PHP le fournit
     * @param int   $projectId  ID du projet cible
     * @param int   $existCount Nombre d'images déjà présentes (détermine la couverture)
     * @return array            Tableau des messages d'erreur (vide si tout OK)
     */
    public function storeImages(array $rawFiles, int $projectId, int $existCount = 0): array
    {
        if (empty($rawFiles['name'][0])) {
            return [];
        }

        $uploadErrors = [];

        foreach (array_keys($rawFiles['name']) as $i) {

            if ($rawFiles['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $singleFile = [
                'name'     => $rawFiles['name'][$i],
                'type'     => $rawFiles['type'][$i],
                'tmp_name' => $rawFiles['tmp_name'][$i],
                'error'    => $rawFiles['error'][$i],
                'size'     => $rawFiles['size'][$i],
            ];

            try {
                // Première image du lot = couverture uniquement si le projet
                // n'en a pas encore ($existCount === 0)
                $isCover = ($existCount === 0 && $i === 0);
                $this->uploadFile($singleFile, $projectId, $isCover, $existCount + $i);

            } catch (UploadException $e) {
                $uploadErrors[] = '"' . $rawFiles['name'][$i] . '" : ' . $e->getMessage();
            }
        }

        return $uploadErrors;
    }

    /**
     * Définit une image comme couverture du projet.
     * Appelée par ProjectController::assignCoverImage().
     *
     * Logique en deux temps obligatoires :
     * 1. Retirer is_cover sur TOUTES les images du projet
     * 2. Poser is_cover sur l'image choisie
     */
    public function defineCoverImage(int $imageId, int $projectId): void
    {
        $this->imageRepository->clearCoverByProject($projectId);
        $this->imageRepository->setCoverImage($imageId);
    }

    /**
     * Supprime une image : fichier physique + entrée BDD.
     * Appelée par ProjectController::removeImage().
     *
     * Si c'était la couverture, la première image restante prend le relais
     * automatiquement via promoteFirstImageAsCover().
     */
    public function deleteImageFile(int $imageId, int $projectId): void
    {
        $image = $this->imageRepository->findImageById($imageId);

        if ($image === null) {
            return;
        }

        // Suppression du fichier physique.
        // On utilise UPLOAD_DIRECTORY du .env — même source que lors de l'upload,
        // ce qui garantit que le chemin est toujours cohérent.
        $this->removePhysicalFile($image->getFilename());

        // Suppression de l'entrée en BDD
        $this->imageRepository->destroyImage($imageId);

        // Si c'était la couverture, promouvoir la première image restante
        if ($image->getIsCover()) {
            $this->imageRepository->promoteFirstImageAsCover($projectId);
        }
    }

    /**
     * Supprime toutes les images physiques d'un projet.
     * Appelée par ProjectService::deleteProject() AVANT la suppression BDD,
     * car le CASCADE supprime les entrées mais pas les fichiers sur le disque.
     *
     * @param array $images Tableau d'objets Image
     */
    public function deleteAllProjectFiles(array $images): void
    {
        foreach ($images as $image) {
            $this->removePhysicalFile($image->getFilename());
        }
    }

    // Méthodes privées

    /**
     * Uploade un fichier unique, valide et insère en BDD.
     */
    private function uploadFile(
        array $file,
        int   $projectId,
        bool  $isCover   = false,
        int   $sortOrder = 0
    ): string {
        if (!isset($file["tmp_name"]) || empty($file["tmp_name"])) {
            throw new UploadException("Pas de fichier à importer");
        }
        if ($file["error"] !== UPLOAD_ERR_OK) {
            throw new UploadException("Erreur upload : code " . $file["error"]);
        }
        if (!is_uploaded_file($file["tmp_name"])) {
            throw new UploadException("Fichier uploadé invalide");
        }
        if ($file["size"] > $this->uploadSizeMax) {
            throw new UploadException("Fichier trop lourd (max " . ($this->uploadSizeMax / 1048576) . " Mo)");
        }

        $ext = Tools::getFileExtension($file["name"]);
        if (empty($this->uploadFormats) || !in_array($ext, $this->uploadFormats, true)) {
            throw new UploadException("Format non autorisé : " . $ext);
        }

        $newName = $this->buildFilename($ext);
        $uploadTarget = $this->uploadtarget . $newName;

        if (!is_dir($this->uploadtarget) || !is_writable($this->uploadtarget)) {
            throw new UploadException("Dossier d'upload inaccessible : " . $this->uploadtarget);
        }
        if (!move_uploaded_file($file["tmp_name"], $uploadTarget)) {
            throw new UploadException("Échec du déplacement du fichier");
        }

        $image = new Image($newName, $isCover, $sortOrder, $projectId);
        $image->setAltText(pathinfo($file["name"], PATHINFO_FILENAME));
        $this->imageRepository->saveImage($image);

        return $newName;
    }

    /**
     * Supprime un fichier physique depuis le dossier d'upload.
     * Silencieux si le fichier est introuvable (pas d'exception).
     */
    private function removePhysicalFile(string $filename): void
    {
        $filePath = $this->uploadtarget . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Génère un nom de fichier unique et non devinable.
     * uniqid() seul peut produire des collisions en boucle rapide —
     * random_bytes(8) garantit l'unicité même pour de nombreux fichiers simultanés.
     */
    private function buildFilename(string $ext): string
    {
        return uniqid("img_") . "_" . bin2hex(random_bytes(8)) . "." . $ext;
    }
}
