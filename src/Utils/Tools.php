<?php

namespace App\Utils;

class Tools
{
    /**
     * Méthode pour sanitize les données utilisateurs
     * @param string $str chaine de caractère à nettoyer
     * @return string chaine nettoyée
     */
    public static function sanitize(string &$str): string 
    {
        $str = trim($str);
        $str = strip_tags($str);
        $str = stripslashes($str);
        $str = htmlspecialchars($str, ENT_NOQUOTES);
        return $str;
    }

    /**
     * Méthode pour sanitize un tableau
     * @param array $data 
     * @return array $data retourne le tableau sanitize
     */
    public static function sanitize_array(array &$data): array
    {
        //Boucle pour itérer sur le tableau $data
        foreach ($data as $key => $value) {
            //Test si la valeur est de type string
            if (gettype($value) == "string") {
                $data[$key] = self::sanitize($value);
            }
            //Test si $value est un tableau
            if (gettype($value) == "array") {
                //nettoyage du sous tableau
                foreach ($value as $skey => $content) {
                    $data[$key][$skey] = self::sanitize($content);
                }
            }
        }
        return $data;
    }

    /**
     * Méthode qui retourne l'extension d'un fichier
     * @param string $file nom du fichier
     * @return string extension du fichier
     */
    public static function getFileExtension($file)
    {
        return strtolower(substr(strrchr($file, '.'), 1));
    }
}
