# Comment collaborer sur le projet

## Récuperation du projet
Utiliser la commande `git clone` pour récupérer le projet dans un premier temps

## Création d'une branch git
Pour pouvoir contribuer sur le projet sans compromettre le projet déjà existant et fonctionnel créer une nouvelle branche avec pour nom la feature que vous voulez mettre en place.
Respecter bien le principe une feature = une branche. Cela permettra l'intégration de votre développement plus facilement sur le projet, sans attendre toutes vos nouvelles fonctionnalités.

## Création d'issue
Vous avez la possibilitée de créer des issues sur le projet pour lister vos tâches à faire.

## Mise en place de test Unitaire
Lors du développement de votre branch, vous devez mettre en place des tests Unitaires via php-unit
Faite en sorte que le taux de couverture de code soit au moins de 70% - https://symfony.com/doc/current/testing.html 

## Création de pull request
Une fois le développement de l'une de vos branches terminées et donc d'une de vos nouvelles fonctionnalités terminées.
Vous pouvez faire une pull request de cette branch mais avant verifier bien votre code :
- Bien indenter le code
- Suppression des lignes non nécessaire au projet (ex: commentaire non pertinent, dump ou dd ...)
- Nom des methods pertinents
- Chaque methods a une description/commentaire
- Respecter les normes PSR (1, 2, 4, 12) que suis Symfony
  - https://www.php-fig.org/psr/
  - https://symfony.com/doc/5.4/contributing/code/standards.html
  - https://symfony.com/doc/5.4/index.html
- Tester la branch sous SymfonyInsight
  - https://insight.symfony.com/
- Tester la performance du code avec BlackFire
  - https://blackfire.io/docs/introduction


