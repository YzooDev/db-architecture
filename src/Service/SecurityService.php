<?php

namespace App\Service;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Utils\Tools;
use App\Service\Exception\UploadException;
use App\Service\UploadService;

class SecurityService 
{
    private AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function logout(): void 
    {
        //détruire la session
        session_destroy();
        //Supprime le cookie
        unset($_COOKIE["PHPSESSID"]);
        //Redirection vers accueil
        header('Location: /');
        // echo "déconnecté";
        // header("Refresh:2; url=/");
    }

    public function login(array $account): string 
    {
        //1 vérifier si les champs sont remplis
        if (
            empty($account["username"]) ||
            empty($account["password"])
        ) {
            return "Veuillez remplir tous les champs du formulaire";
        }

        //2 nettoyer les données
        Tools::sanitize_array($account);

        //3 Récupération du compte
        $user = $this->accountRepository->findAccountByUsername($account["username"]);

        //5 vérifier si le compte n'existe pas
        if ($user == null) {
            return "Les informations de connexion sont incorrectes";
        }

        if (!$user->verifyPassword($account["password"])) {
            return "Les informations de connexion sont incorrectes";
        }
        //Super globale de session
        $_SESSION["connected"] = true;
        $_SESSION["email"] = $user->getEmail();
        $_SESSION["username"] = $user->getUsername();
        $_SESSION["id"] = $user->getId();
        
        return "Vous etes connecté";
    }
}