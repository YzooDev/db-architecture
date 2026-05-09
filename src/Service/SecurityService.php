<?php

namespace App\Service;

use App\Repository\AccountRepository;
use App\Utils\Tools;

class SecurityService 
{
    private AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function logout(): void 
    {
        session_destroy();
        unset($_COOKIE["PHPSESSID"]);
        header('Location: /');
    }

    public function login(array $account): string 
    {
        if (
            empty($account["username"]) ||
            empty($account["password"])
        ) {
            return "Veuillez remplir tous les champs du formulaire";
        }

        Tools::sanitize_array($account);

        $user = $this->accountRepository->findAccountByUsername($account["username"]);

        if ($user == null) {
            return "Les informations de connexion sont incorrectes";
        }

        if (!$user->verifyPassword($account["password"])) {
            return "Les informations de connexion sont incorrectes";
        }
        
        $_SESSION["connected"] = true;
        $_SESSION["email"] = $user->getEmail();
        $_SESSION["username"] = $user->getUsername();
        $_SESSION["id"] = $user->getId();
        
        return "Vous etes connecté";
    }
}