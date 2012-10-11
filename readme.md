#What are Database Migrations?

Migrations are a convenient way for you to alter your database in a structured and organized manner. You could edit fragments of SQL by hand but you would then be responsible for telling other developers that they need to go and run them. You’d also have to keep track of which changes need to be run against the production machines next time you deploy.

Above from [Rails guide](http://guides.rubyonrails.org/migrations.html).

This Migrations library was inspired by earlier works such as [mysql-php-migrations](https://github.com/davejkiger/mysql-php-migrations),
and implementations found in both Codeigniter and Fulephp frameworks.

##Whats different?

1. Written with php 5.3 and uses [Symfony2](http://symfony.com/components) components and [Doctrine DBAL](http://www.doctrine-project.org/projects/dbal.html)
2. Allows each project to define templates using [Twig](http://twig.sensiolabs.org/).
3. Uses Doctrine DBAL Schema manager to write platform independent migrations or use normal SQL DDL to control your database.
4. All commands accept a [DSN](http://en.wikipedia.org/wiki/Data_Source_Name) allowing scripting to apply your migrations to many databases.

##Getting Started

###Installing

This library can be accessed through Composer

Using dev mode as most likely don't want this component in a release cycle.

Create composer.json add add the following.

```json
{
    require : {
    },
    "require-dev" : {
        "icomefromthenet/migration" : "dev-master" 
    }
 }
```

##Running the commands

***Create the project folder and then run the int function using the vendor bin migrate.php. Note all commands are prefixed with `app:`***

    mkdir migrations
    cd migrations
    ../vendor/bin/migrate.php app:init 

***Create the Config for your database answer the questions and a config will be created.***

    ../vendor/bin/migrate.php app:config 

***Run install to add migrations tacking database table to the schema:***

    ../vendor/bin/migrate.php app:install 

***Add your first migration by using the add command (optional description slug):***

    ../vendor/bin/migrate.php app:add #prefix# 

***Run up command to install the change***

    ../vendor/bin/migrate.php app:up 1

***Run status to find the head migration***

    ../vendor/bin/migrate.php app:status

***Run status to find the head migration***

    ../vendor/bin/migrate.php app:status


Requirements
----------------

* php 5.3
* CLI.
* SPL
* PDO
* Composer
