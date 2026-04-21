<?php

include '../vendor/autoload.php';

//démarrage de la session
session_start();

//Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->load();

//Récupération de l'URL
$url = parse_url($_SERVER['REQUEST_URI']);

//test soit l'url a une route sinon on renvoi à la racine
$path = isset($url['path']) ? $url['path'] : '/';

//Importer les controllers
use App\Controller\HomeController;

//instancier les controllers
$homeController = new HomeController();

//Routeur (test)
switch ($path) {
    case '/':
        $homeController->index();
        break;
    default:
        echo "404 la page n'existe pas";
        break;
}