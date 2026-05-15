<?php

namespace App\Service;

use App\Utils\Tools;
use App\Service\Exception\UploadException;
use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Repository\ProjectRepository;

class UploadService
{
    private readonly string $uploadtarget;
    private readonly int $uploadSizeMax;
    private array $uploadFormats;
    private ImageRepository $imageRepository;
    private ProjectRepository $projectRepository;

    public function __construct()
    {
        $this->uploadtarget = rtrim($_ENV["UPLOAD_DIRECTORY"], "/\\") . DIRECTORY_SEPARATOR;
        $this->uploadSizeMax = (int) $_ENV["UPLOAD_SIZE_MAX"];
        $this->uploadFormats = json_decode($_ENV["UPLOAD_FORMAT_WHITE_LIST"], true) ?? [];
        $this->imageRepository = new ImageRepository();
        $this->projectRepository = new ProjectRepository();
    }

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
                'name' => $rawFiles['name'][$i],
                'type' => $rawFiles['type'][$i],
                'tmp_name' => $rawFiles['tmp_name'][$i],
                'error' => $rawFiles['error'][$i],
                'size' => $rawFiles['size'][$i],
            ];

            try {
                $isCover = ($existCount === 0 && $i === 0);
                $this->uploadFile($singleFile, $projectId, $isCover, $existCount + $i);

            } catch (UploadException $e) {
                $uploadErrors[] = '"' . $rawFiles['name'][$i] . '" : ' . $e->getMessage();
            }
        }
        return $uploadErrors;
    }

    public function defineCoverImage(int $imageId, int $projectId): void
    {
        $this->imageRepository->clearCoverByProject($projectId);
        $this->imageRepository->setCoverImage($imageId);
    }

    public function deleteImageFile(int $imageId, int $projectId): void
    {
        $image = $this->imageRepository->findImageById($imageId);

        if ($image === null) {
            return;
        }

        $project = $this->projectRepository->findProjectById($projectId);

        if (count($project->getImages()) <= 1) {
            return;
        }

        $this->removePhysicalFile($image->getFilename());
        $this->imageRepository->destroyImage($imageId);

        if ($image->getIsCover()) {
            $this->imageRepository->setFirstImageAsCover($projectId);
        }
    }

    public function deleteAllProjectFiles(array $images): void
    {
        foreach ($images as $image) {
            $this->removePhysicalFile($image->getFilename());
        }
    }

    private function uploadFile(
        array $file,
        int $projectId,
        bool $isCover   = false,
        int $sortOrder = 0
    ): string 
    {
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

    private function removePhysicalFile(string $filename): void
    {
        $filePath = $this->uploadtarget . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function buildFilename(string $ext): string
    {
        return uniqid("img_") . "_" . bin2hex(random_bytes(8)) . "." . $ext;
    }
}
