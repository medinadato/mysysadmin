mysysadmin
==========

System to manage your servers, domains and users. 

# Install

## Vendors

In order to install the libraries of the system run composer:

$ composer.phar install --prefer-dist

## Virtual host

### Nginx

To install the Nginx's Virtual host check the folder docs/nginx.conf 

### Apache

Well, if you using Apache, you are on your own. Shouldn't be hard, though. 

## Database

You can forward engineer the tables from docs/db/data modeling/mysysadmin.mwb or run docs/db/dump/mysysadmin.sql

Tip: Don't forget to create the database/user prior to import the tables:

$ mysql -uroot -p -e "CREATE DATABASE mysysadmin CHARACTER SET utf8 COLLATE utf8_general_ci";
$ mysql -uroot -p -e "GRANT ALL PRIVILEGES ON mysysadmin.* TO 'root'@'%' IDENTIFIED BY 'mypass' WITH GRANT OPTION";
