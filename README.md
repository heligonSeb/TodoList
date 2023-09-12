# ToDo & Co - todolist
project 8 openclassroom pour la formation développeur PHP Symfony

## Présentation
Venant juste d'arriver dans une startup mon objectif est d'améliorer une application permettant de gérer ses tâches quotidiennes réalisé sous Symfony.
Mes objectifs consistent donc à améliorer :
- la qualité du code
- la qualité perçue par l'utilisateur
- la qualité perçue par les collaborateurs
- la qualité lorsqu'il vous faut travailler sur le projet

Mais aussi :
- L'implémentation de nouvelles fonctionnalités
- La correction de quelques anomalies
- L'implémentation de tests automatisés
- Documentation technique
- Documentation d'audit de qualité du code et performance de l'application

## Installation du projet
Dans un premier temps récupérer le projet sur git avec `git clone`

### Composer
Via composer, on va venir installer les dépendances du projet avec la commande 
```shell
composer install
```

### Configuration du fichier .env.local
Pour utiliser une base de donnée il vous faudra configurer votre propre ficher `.env.local` avec vos propres informations.
Vous pouvez prendre en exemple le ficher `.env` deja existant dans le projet

### Création de la base de donnée
```shell
php bin/console doctrine:database:create
```

### Configuration de la base de données
```shell
php bin/console doctrine:schema:update
```

### Mise en place des données dans la base de donnée
```shell
php bin/console doctrine:fixtures:load
```

## Début du projet
Une fois les étapes précédentes finies, vous pouvez lancer le serveur avec la commande
```shell
symfony server:start
```

Une fois le serveur lancé vous pouvez accéder au projet avec l'adresse http://localhost:8000/


