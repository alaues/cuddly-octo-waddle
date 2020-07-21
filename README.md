# Overview

Test API based on Lumen framework

# Installation

* clone project into directory

* install dependencies

    ` php composer.phar install`
 
* create database on your MySQL server 

* specify database credentials in `.env` file

* run migration
   
   `php artisan migrate`
   
* application is ready<br> API specification is located in `API.md` file

# Tests

Tests are available in directory `tests`<br>

To run tests, execute

   `vendor/bin/phpunit tests`
