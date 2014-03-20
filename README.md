mysysadmin
==========

System to manage the information related to your servers, domains, users and so forth. 

# Install

## Vendors

In order to install the libraries of the system run composer:

$ composer.phar install --prefer-dist

## Virtual host

### Nginx

To install the Nginx's Virtual host check the folder [docs/nginx.conf](https://github.com/medinadato/mysysadmin/blob/master/docs/ngnix/mysysadmin.com.conf) 

### Apache

Well, if you using Apache, you are on your own. it should not be hard, though. 

## Database

### Via SQL
You can forward engineer the tables from [mysysadmin.mwb](https://github.com/medinadato/mysysadmin/blob/master/docs/db/data%20modeling/mysysadmin.mwb) or run [mysysadmin.sql](https://github.com/medinadato/mysysadmin/blob/master/docs/db/dump/mysysadmin.sql)

Tip: Don't forget to create the database/user prior to import the tables:

```mysql
$ mysql -uroot -p -e "CREATE DATABASE mysysadmin CHARACTER SET utf8 COLLATE utf8_general_ci";
$ mysql -uroot -p -e "GRANT ALL PRIVILEGES ON mysysadmin.* TO 'mysysadmin'@'%' IDENTIFIED BY 'mypass' WITH GRANT OPTION";

### Via Symfony Console

