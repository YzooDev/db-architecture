# db-architecture
Projet de site vitrine pour le stage de fin de formation

## 1 Installer les dépendances :
```sh
composer install
composer require phpmailer/phpmailer
```
## 2 Créer un fichier .env
```env
DATABASE_USERNAME=root
DATABASE_PASSWORD=
DATABASE_NAME=dba
DATABASE_HOST=localhost:3306
```

## 3 démarrer le projet
```sh
php -S 127.0.0.1:8000 -t public
```

## 4 Ajouter les variables d'environnement suivantes :
UPLOAD_DIRECTORY="assets/img/"
UPLOAD_SIZE_MAX=2097152
UPLOAD_FORMAT_WHITE_LIST='["png", "jpeg", "jpg", "webp"]'

