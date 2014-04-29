mysysadmin
==========

System to manage the information related to your servers, domains, users and so forth. 

# Compatibilities

This application should run on PHP 5.4 or superior and MySQL + 5.0.

# Install

## Vendors

In order to install the libraries of the system run composer on the root of the project:

    $ composer.phar install --prefer-dist

## Virtual host

### Nginx

To install the Nginx's Virtual host check the folder [docs/nginx.conf](https://github.com/medinadato/mysysadmin/blob/master/docs/ngnix/mysysadmin.com.conf) 

### Apache

Well, if you using Apache, you are on your own. it should not be hard, though. 

## Hosts

If you working locally, you might setup your HOSTS file

    XX.X.X.XXX      local.mysysadmin.com

## Initial Data

You need either add the user below into your database or change the connection info at app/config/config_dev.yml

    $ mysql -uroot -p -e "GRANT ALL PRIVILEGES ON mysysadmin.* TO 'mysysadmin'@'localhost' IDENTIFIED BY 'mypass' WITH GRANT OPTION";

There are 2 ways to load the database;

### Via SQL

You can forward engineer the tables from [mysysadmin.mwb](https://github.com/medinadato/mysysadmin/blob/master/docs/db/data%20modeling/mysysadmin.mwb) or run [mysysadmin.sql](https://github.com/medinadato/mysysadmin/blob/master/docs/db/dump/mysysadmin.sql)

Tip: Don't forget to create the database/user prior to import the tables:

    $ mysql -uroot -p -e "CREATE DATABASE mysysadmin CHARACTER SET utf8 COLLATE utf8_general_ci";

### Via Symfony Console

1. Database structure

	ATTENTION: This operation should not be executed in a production environment

    $ php app/console doctrine:database:create --env=dev
    $ php app/console doctrine:schema:create --env=dev --em=default

2. Initial data

    $ php app/console doctrine:fixtures:load --env=dev --no-interaction

## Directory permissions

You might or might not need to run

    $ sudo chmod -R 744 .

# Application

## Assets

Composer usually install the assets when you run it. Otherwise you can it yourself.
I strongly recommend to create the assets as symbolic links. To do so:

    $ php app/console assets:install --symlink

# Access

You should be able to access the server by the data:

url:  http://local.mysysadmin.com/admin
user: admin@mdnsolutions.com
pass: 123456




