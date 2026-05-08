<?php

declare(strict_types=1);

include '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->load();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

use App\Controller\HomeController;
use App\Controller\ProjectController;
use App\Controller\ContactController;
use App\Controller\SecurityController;
use App\Controller\UploadController;

switch (true) {

    // Pages publiques 

    case $path === '/':
        (new HomeController())->index();
        break;

    case $path === '/project':
        (new ProjectController())->showAllProject();
        break;

    case (bool) preg_match('#^/project/(\d+)$#', $path, $data):
        (new ProjectController())->showProject((int) $data[1]);
        break;

    case $path === '/contact':
        (new ContactController())->handle();
        break;

    // Authentification

    case $path === '/admin':
        (new SecurityController())->connection();
        break;

    case $path === '/admin' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new SecurityController())->connection();
        break;

    case $path === '/logout':
        (new SecurityController())->disconnection();
        break;

    // Admin projets 

    case $path === '/admin/project':
        (new ProjectController())->listProjects();
        break;

    case $path === '/admin/project/new':
        (new ProjectController())->createProject();
        break;

    case $path === '/admin/project/new' && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new ProjectController())->createProject();
        break;

    case (bool) preg_match('#^/admin/project/(\d+)$#', $path, $data):
        (new ProjectController())->showAdminProject((int) $data[1]);
        break;

    case (bool) preg_match('#^/admin/project/(\d+)/edit$#', $path, $data):
        (new ProjectController())->editProject((int) $data[1]);
        break;

    case (bool) preg_match('#^/admin/project/(\d+)/edit$#', $path, $data) && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new ProjectController())->updateProject((int) $data[1]);
        break;

    case (bool) preg_match('#^/admin/project/(\d+)/delete$#', $path, $data) && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new ProjectController())->removeProject((int) $data[1]);
        break;

    // Admin gestion images d'un projet

    case (bool) preg_match('#^/admin/project/(\d+)/image/store$#', $path, $data) && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new ProjectController())->storeImages((int) $data[1]);
        break;

    case (bool) preg_match('#^/admin/project/(\d+)/image/(\d+)/cover$#', $path, $data) && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new ProjectController())->assignCoverImage((int) $data[1], (int) $data[2]);
        break;

    case (bool) preg_match('#^/admin/project/(\d+)/image/(\d+)/delete$#', $path, $data) && $_SERVER['REQUEST_METHOD'] === 'POST':
        (new ProjectController())->removeImage((int) $data[1], (int) $data[2]);
        break;

    default:
        http_response_code(404);
        $title = 'Page introuvable';
        include __DIR__ . '/../template/template_404.php';
        break;
}
