<?php

namespace App\Controller;

abstract class AbstractController
{
    protected function render(string $template, ?string $title, array $data = []): void
    {
        extract($data);

        $templatePath = __DIR__ . "/../../template/template_" . $template . ".php";

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template introuvable : template_{$template}.php");
        }

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
