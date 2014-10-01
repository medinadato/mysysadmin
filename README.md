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

Database structure

> ATTENTION: This operation should not be executed in a production environment

    $ php app/console doctrine:database:create --env=dev
    $ php app/console doctrine:schema:create --env=dev --em=default

### Authentication data

    $ php app/console doctrine:fixtures:load --env=dev --no-interaction

## Directory permissions

You might or might not need to run

    $ sudo chmod -R 744 .
    
You also might or might not need to run:
    $ sudo chmod 666 /var/run/php5-fpm.sock

# Application

## Assets

Composer usually install the assets when you run it. Otherwise you can it yourself.
I strongly recommend to create the assets as symbolic links. To do so:

    $ php app/console assets:install --symlink

# Access

You should be able to access the server by the data:

URL:  [http://local.mysysadmin.com/admin](http://local.mysysadmin.com/admin) 
USER: admin[at]mdnsolutions.com
PASS: 123456



# Creating a CRUD

This section I show an example of how to create your own custom CRUD module.
In our case it's a new CRUD for the table Server in our MDN\AdminBundle.

## Database Table

Create your database table (e.g.: Server).

### Mapping and Entities

In order to map your entities into the application, run in your command line:

    $ php app/console doctrine:mapping:import --em=default --filter='Server' MDNAdminBundle yml

Then create the entity class file by:

    $ php app/console doctrine:mapping:convert annotation ./src --em=default --filter='Server'

By now you should be able to see the new file MDN\AdminBundle\Entity\Server.php created.

Now, add the Getters and Setters by using 

    $ php app/console doctrine:generate:entities MDN/AdminBundle/Entity/Server

You can also delete the files on the directory src/MDN/AdminBundle/Resources/config/doctrine/.

## Controller

Create your controller in src/MDN/AdminBundle/Controller/ServerController.php
This step includes creating the view as well.

### Grid

You might use the grid module available. To do so, register your grid as a service and load it 
in your controller src/MDN/AdminBundle/Resources/config/services/grid.yml.


# Extra libraries' documentations

[APYGridBundle](https://github.com/Abhoryo/APYDataGridBundle/blob/master/Resources/doc/summary.md)


# More at:
[http://blog.mdnsolutions.com](http://blog.mdnsolutions.com)