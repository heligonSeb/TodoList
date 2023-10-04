# How to collaborate on the project

## Get the project
Use the `git clone` command to get the project

## Create new git branch
To be able to contribute to the project without compromising the already existing and functional project, create a new branch with the name of the feature that you want to implement.
Respect the principle of one feature = one branch. This will allow your development to be integrated more easily into the project, without waiting for all your new features.

## Create new issues
You can create issues on the project to list your tasks to do.

## Unit test implementation
When developing your branch, you must set up unit tests via php-unit
Make the code coverage rate at least 70% - https://symfony.com/doc/current/testing.html

You can use this commande for generate your test-coverage and read the file resultTest.txt
```shell
php bin/phpunit --coverage-html=tests/test-coverage >resultTest.txt
```

## Create pull request
Once the development of one of your branches is complete and one of your new features is complete.
You can make a pull request from this branch but first check your code carefully:
- Indent the code correctly
- Delete lines not necessary for the project (eg: irrelevant comment, dump or dd ...)
- Name of relevant methods
- Each method has a description/comment
- Respect the PSR standards (1, 2, 4, 12) that Symfony follows
  - https://www.php-fig.org/psr/
  - https://symfony.com/doc/5.4/contributing/code/standards.html
  - https://symfony.com/doc/5.4/index.html
- Test the branch under SymfonyInsight
  - https://insight.symfony.com/
- Test code performance with BlackFire
  - https://blackfire.io/docs/introduction


