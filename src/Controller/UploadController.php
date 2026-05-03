<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\UploadService;

class UploadController extends AbstractController
{
    private UploadService $uploadService;
    
    public function __construct()
    {
        $this->uploadService = new UploadService();
    }
    
    //Méthode pour gérer l'affichage de la page de téléchargement d'images
    public function uploadImage()
    {
        if (!isset($_SESSION["connected"])) header('Location:/');

        $data = [];

        if(isset($_POST["submit"])) {
            if(isset($_FILES["image"])) {
                $data["msg"] = $this->uploadService->uploadFile($_FILES);
            }    
        }

        return $this->render("add_image","Ajouter des images", $data);
    }
}