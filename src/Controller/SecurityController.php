<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Service\SecurityService;

class SecurityController extends AbstractController
{
    private SecurityService $securityService;

    public function __construct()
    {
        $this->securityService = new SecurityService();
    }

    public function connection(): void
    {
        if (!empty($_SESSION["connected"])) {
            $this->redirect('/admin/project');
        }

        $data = [];

        if (isset($_POST["submit"])) {
            $data["msg"] = $this->securityService->login($_POST);

            if ($data["msg"] === "Vous etes connecté") {
            
                session_regenerate_id(true);
                $this->redirect('/admin/project');
            }
        }

        $this->render("login", "Connexion", $data);
    }

    public function disconnection(): void
    {
        $this->securityService->logout();
    }
}
