# ToDo & Co - todolist
project 8 openclassroom - développeur PHP Symfony

## Presentation
Having just arrived in a startup, my goal is to improve an application allowing you to manage your daily tasks using Symfony.
My objectives consist of improving:
- code quality
- the quality perceived by the user
- the quality perceived by employees
- quality when work on the project

But also :
- Implementation of new features
- Fixed some anomalies
- Implementation of automated tests
- Technical documentation
- Code quality audit documentation and application performance

## Install the project
Firstly recover the project on git with `git clone`

### Composer
We will install the project dependencies with:
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


