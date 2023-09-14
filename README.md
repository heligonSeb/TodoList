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

### Configuration .env.local
To use a database you will need to configure your own `.env.local` file with your own information.
You can take as an example the `.env` file already existing in the project

### Database creation
```shell
php bin/console doctrine:database:create
```

### Database configuration
```shell
php bin/console doctrine:schema:update
```

### Add data in the database
```shell
php bin/console doctrine:fixtures:load
```

## Beginning the project
Once the previous steps have been completed, you can launch the server with the command
```shell
symfony server:start
```

Once the server is launched you can access the project with the link http://localhost:8000/


