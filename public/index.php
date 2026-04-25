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
use App\Controller\ProjectController;
use App\Controller\ContactController;
use App\Controller\SecurityController;
use App\Controller\TestController;

//instancier les controllers
$homeController = new HomeController();
$projectController = new ProjectController();
$contactController = new ContactController();
$securityController = new SecurityController();
$testController = new TestController();


//Routeur (test)
switch ($path) {
    case '/':
        $homeController->index();
        break;
    case '/project':
        $projectController->showAllProject();
        break;
    case '/contact':
        $contactController->index();
        break;
    case '/admin':
        $securityController->connection();
        break;
    case '/logout':
        $securityController->disconnection();
        break;
    case '/admin/project':
        $projectController->manageProject();
        break;
    case '/admin/project/new':
        $projectController->createProject();
        break;
    default:
        echo "404 la page n'existe pas";
        break;
}