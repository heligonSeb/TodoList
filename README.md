# ToDo&Co - todolist
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
The docker-compose environment does not require setting up a database. 
If you want to use a database already installed on your machine, create an .env.local file containing the information to connect to the database

### Docker compose
```shell
docker-compose up
```

### Database configuration
```shell
symfony console doctrine:migrations:migrate
```

### Add data in the database
```shell
symfony console doctrine:fixtures:load
```

## Beginning the project
Once the previous steps have been completed, you can launch the server with the command
```shell
symfony server:start
```

Once the server is launched you can access the project with the link http://localhost:8000/


