<?php

namespace App\Controller;

abstract class AbstractController
{
    protected function render(string $template, ?string $title, array $data = []): void
    {
        include __DIR__ . "/../../template/template_" . $template . ".php";
    }

    /**
     * Méthode pour gérer les accés connecté
     * @return void
     */
    protected function isConnected(): void
    {
        //Si utilisateur non connecté
        if (
            !isset($_SESSION["connected"])) {
            header('Location:/');
        }
    }
}
