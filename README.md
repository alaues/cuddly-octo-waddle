# Overview

Test API based on Lumen framework

# Requirements

* Lumen framework

* php 7.4

* MySQL database

* composer

* phpunit

# Installation

* clone project into directory

* install dependencies

    ` php composer.phar install`
 
* create database on your MySQL server 

* specify database credentials in `.env` file

* specify SMTP credentials in `.env` file

* run migration
   
   `php artisan migrate`
   
* application is ready<br> 

# API specification

API specification is located  [here](./API.md)

# Tests

Tests are available in directory `tests`<br>

To run tests, execute

   `vendor/bin/phpunit tests`
