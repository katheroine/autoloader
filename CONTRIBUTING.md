# Contributing to Autoloader

To contribute to the project you need to have an account on [GitHub](https://github.com/).

## Workflow

1. Create a fork of this repository
2. Clone repository
3. Ensure you have configured *name* and *email address* in Git
4. Create issue with proper description and label on GitHub within this project
5. Assign yourself to the issue
6. Fork fix or feature branch with proper prefix and number of the issue (e.g. *issue-92-readme-file-update*)
7. Complete work and commit with proper message and number of issue with hash and in parentheses
8. Push branch to your forked project on your GitHub account
9. Create a [pull request](https://help.github.com/articles/using-pull-requests/) to the **develop** branch of the original ExOrg repository

## Standards and conventions

Please follow [PSR-1](http://www.php-fig.org/psr/psr-1/) and [PSR-12](http://www.php-fig.org/psr/psr-12/) coding standards and [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading standard.

This project uses [PHPCodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for detecting coding style issues.

To run tests for code style  write the following command in your command line inside the main Autoloader project directory

```bash
vendor/bin/phpcs tests/ src/
```

Please take care over consistency of the code and check existing examples before you create your own extensions.

## Automated tests

Write unit tests for your code.

This project uses [PHPUnit](https://phpunit.de/) as unit tests framework.

To run tests, write the following command in your command line inside the main Autoloader project directory

```bash
vendor/bin/phpunit tests/
```