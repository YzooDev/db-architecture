<?php

namespace App\Repository;

use App\Database\Mysql;
use App\Entity\Account;

class AccountRepository
{
    private \PDO $connect;

    public function __construct()
    {
        $this->connect = Mysql::connectBdd();
    }


    public function findAccountByUsername(string $username): ?Account
    {
        try {
            //1 Ecrire la requête,
            $sql = "SELECT a.id_account, a.email, a.username, a.password FROM `account` AS a
            WHERE a.username = ?";
            //2 Préparer la requête,
            $req = $this->connect->prepare($sql);
            //3 Assigner le paramètre,
            $req->bindParam(1, $username, \PDO::PARAM_STR);
            //4 Exécuter la requête,
            $req->execute();
            //5 Fetch en FETCH assoc + hydratation en Account
            $account = $req->fetch(\PDO::FETCH_ASSOC);
            //6 test si account Existe
            if ($account != false) {
                //Hydratation en Account
                return $this->hydrateAccount($account);
            }
            return null;
        } catch(\PDOException $e) {}
        return null;
    }

    /**
     * Méthode pour Hydrater en Account
     * @param array $row ligne d'enregistrement SQL
     * @return Account Objet Account
     */
    public function hydrateAccount(array $row): Account 
    {
        $account = new Account($row["username"], $row["password"]);
        $account->setId($row["id_account"]);
        $account->setEmail($row["email"]);
        return $account;
    }
}

