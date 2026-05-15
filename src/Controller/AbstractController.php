<?php

namespace App\Controller;

abstract class AbstractController
{
    private array $metaDescriptions = [
        'home' => 'Daniel Bezes, architecte DPLG indépendant basé dans le Tarn. Découvrez ses réalisations à Toulouse, dans le Sud-Ouest et dans toute la France.',
        'project' => 'Portfolio de Daniel Bezes, architecte DPLG. Parcourez ses projets architecturaux à Toulouse, dans Sud-Ouest et dans toute la France.',
        'contact' => 'Contactez Daniel Bezes, architecte DPLG indépendant. Décrivez votre projet et recevez une réponse rapide pour vos travaux de rénovation ou construction.',
    ];

    private string $metaDefault = 'Daniel Bezes Architecture : Architecte DPLG indépendant dans le Tarn. Tous ses projets à Toulouse, dans le Sud-Ouest et dans toute la France.';

    protected function render(string $template, ?string $title, array $data = []): void
    {
        extract($data);

        if (isset($this->metaDescriptions[$template])) {
            $metaDesc = $this->metaDescriptions[$template];

        } elseif (isset($project)) {
            $metaDesc = mb_substr(strip_tags($project->getDescription()), 0, 120)
                . ' | Daniel Bezes Architecture';

        } else {
            $metaDesc = $this->metaDefault;
        }
        
        $scriptPath = __DIR__ . "/../../public/assets/script/" . $template . ".js";
        $templateScript = file_exists($scriptPath) ? $template : null;

        $templatePath = __DIR__ . "/../../template/template_" . $template . ".php";

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template introuvable : template_{$template}.php");
        }

        $templateData = array_merge($data, [
            'metaDesc' => $metaDesc,
            'templateScript' => $templateScript,
        ]);

        extract($templateData);

        include $templatePath;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function isConnected(): void
    {
        if (!isset($_SESSION["connected"])) {
            $this->redirect('/admin');
        }
    }
}
