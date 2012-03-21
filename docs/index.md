
PROJECT NOT COMPLETE
==============

currenty in development, please comeback later.

Migration
===========

What are Database Migrations?

> Migrations are a convenient way for you to alter your database in a structured and organized manner.
> You could edit fragments of SQL by hand but you would then be responsible for telling other developers
> that they need to go and run them. You’d also have to keep track of which changes need to be run
> against the production machines next time you deploy.

Above from [Rails guide](http://guides.rubyonrails.org/migrations.html).


This Migrations library was inspired by earlier libaries such as the [mysql-php-migrations](https://github.com/davejkiger/mysql-php-migrations),
and later migration implementation found in both Codeignter and Fulephp frameworks.

Whats new and different?
------------------------

1. Writtern with php 5.3 and uses [Symfony2](http://symfony.com/components) components and [Doctrine DBAL](http://www.doctrine-project.org/projects/dbal.html)
2. Supports multiple schema's for each project , allows each project to define templates using [Twig](http://twig.sensiolabs.org/).
3. Uses Doctine DBAL Schema manager to write platform independent migrations (optional).
4. All commands accept a [DSN](http://en.wikipedia.org/wiki/Data_Source_Name) which make applying single schema's migrations to many databases easier.

Getting Started
---------------


###Installing

This library can be accessed through PEAR


Also checkout this project and use the commands found in bin folder

##Running the commands

A. Setup the project folder and then run the int function below and supplying the folder you just loacted reccomend to do this in your repository base directory.

<code>mkdir migrations
    migrate-init migrations
    cd migrations
</code>

B. Create the Config for your database (run command under the migrations dir created above) answer the questions and a config will be created a placed under config folder:

<code>migrate config</code>


C. Fill your init-schema and test data, the default schema is located under projectfolder/migrations/default.

D. Run build to install the migrations:

<code>migrate build</code>

E. Add your first migration by using the add command (optional description slug):

<code> migrate add add_new_feature</code>


Requirements
----------------

* php 5.3
* CLI access to server.
