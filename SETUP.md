# Dungeons - Setup

How to setup dungesons all by yourself

## requirements

| Requirement | Source | Note |
| --- | --- | --- |
npm | <https://nodejs.org/en/download/> | 
php | <https://windows.php.net/download/> | php.ini mit folgenden aktivierten Modulen:<br />*extension=pdo_mysql<br />*extension=openssl
composer | <https://getcomposer.org/download/> | 
browser | https://addons.mozilla.org/de/firefox/addon/vue-js-devtools/?src=search |

## 1. Init project

create folder of project, e. g. "dungeons"

## 2. Init server

### 2.1 Create Server

Here we initialise our proud project just by generating a new folder.  
We use the symfony/website-skeleton. 
The server will be available in your browser on http://127.0.0.1:8000

~~~cli
# Building server
# server -S 127.0.0.1:8000
composer create-project symfony/website-skeleton dungeon-server
# -S <addr>:<port> means Run with built-in web server.
# -t <docroot> specifies document root <docroot> for built-in web server.

# enter the dungeon-server
cd dungeon-server

# start the server
php -S 127.0.0.1:8000 -t public
~~~

### 2.2 First Routing Check

Generate first file, a demo controller

~~~php
<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DemoController
{
    /**
     * @Route("/demo")
     */
    public function demonicAction()
    {
        return new JsonResponse([
            [
                'title' => 'The Real Demo',
                'description' => 'Some description',
                'state' => 1
            ]
        ]);
    }
}
~~~

Note the `@Route("/demo")` in the method's comment.  
This actually ist the route, that we can call.  
Go to <http://127.0.0.1:8000/demo>, and symfony will show you the result right away.
Should look a little like this:

~~~json
[{"title":"The Real Demo","description":"Some description","state":1}]
~~~
 
### 2.3 Extend A Little 

To simplifiy things a little (to avoid Returning JsonResponse() all the time, we generate a general src/Controller/ApiController.php.

~~~php
<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }
}
~~~

... and we adjust our already existing DemoController.php

~~~php
<?php
namespace App\Controller;

class DemoController extends ApiController
{
    public function demonicAction()
    {
        return $this->respond([
             [
                'title' => 'The Real Demo',
                'description' => 'Some description',
                'state' => 1
            ]
        ]);
    }
}
~~~ 

### 2.4 Use proper Router

1. Strip @Route in comment from DemoController::demonicAction()) 

2. Add route ro dungeon-server/config/routes.yml

~~~json
index:
  path: /
  controller: App\Controller\DemoController::demonicAction

demoRoute:
  path: /demo
  controller: App\Controller\DemoController::demonicAction
~~~ 

Go to <http://127.0.0.1:8000/demo>, that should still work and return a proper json output.

## 3. Add Database

We want to use a database for storing our data.
Here our steps:   

* create database environment (which we create with docker)
* create table via symfony orm
* check connection

### 3.1 Create Database Environment

To create a database, we use docker.  
So we simply add following files in the document route:

* Dockerfile
* docker-compose.yml
    
Then we call `docker-compose up`.  

**docker-compose.yml**

~~~json
version: '2'
services:
  dungeondb:
    image: mysql:latest
    volumes:
      - ./dump:/docker-entrypoint-initdb.d
      - ./.adv-mysql/databasedata:/var/lib/mysql
    restart: always
    ports:
      - 3306:3306
    expose:
      - 3306
    container_name: dungeon_mysql
    environment:
      MYSQL_DATABASE: dungeondb
      MYSQL_ROOT_PASSWORD: root
      MYSQL_USER: d
      MYSQL_PASSWORD: d
    networks:
      - adventures
  php:
    build: .
    volumes:
      - ".:/var/www/html"
    networks:
      - adventures
  phpmyadmin:
    depends_on:
      - dungeondb
    image: phpmyadmin/phpmyadmin
    restart: always
    ports:
      - '8081:80'
    environment:
      PMA_HOST: dungeon_mysql
      MYSQL_ROOT_PASSWORD: root
    networks:
      - adventures
volumes:
  databasedata:
networks:
  adventures:
~~~ 

**Dockerfile**

~~~cli
FROM php:7.4-apache
COPY ./ /var/www/html/
COPY ./dump /docker-entrypoint-initdb.d/
RUN docker-php-ext-install pdo pdo_mysql mysqli
EXPOSE 80
~~~

Call following comman in the commandline afterwards:

~~~cli
docker-compose up
~~~

The docker-compose.yml consists two services: (1) php container, and (2) phpMyAdmin container.  

You can access phpMyAdmin on <http://127.0.0.1:8081>. 

### 3.2 create table via symfony orm

~~~
# add symfony stuff
composer require sensio/framework-extra-bundle
composer require symfony/orm-pack
~~~
## TODO 

~~~
# server - 127.0.0.1:8000
composer create-project symfony/website-skeleton dungeon-server
cd dungeon-server
php -S 127.0.0.1:8000 -t public

# client - 127.0.0.1:8080
# vue itself uses package.json, so we will use npm consitantly
# npm install -g @vue/cli
npm i -g @vue/cli
vue create dungeon-client
cd dungeon-client
npm run serve

# Create the Symfony Skeleton API
cd dungeon-server
composer require sensio/framework-extra-bundle

# add file:   docker-compose.yml with phpMyAdmin etc.
# add file:   Dockerfile
# add folder: dump/ 
# add file:   dungeon-server/.env (for doctrine, adjust path of service) 
docker-compose up

# create the Symfony Skeleton API
cd dungeon-server
# ORM package
composer require symfony/orm-pack
# Symfony Maker creates empty commands, controllers, form classes, tests (less writing boilerplate code for myself:-)
composer require symfony/maker-bundle --dev
# if new db, then: (here we got the dump db migration, so not needed
# php bin/console make:entity
# migrate
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity
php bin/console make:entity --regenerate App
# got to entity files
# add "* @ORM\Entity(repositoryClass="App\Repository\XxxxxxRepository")" within class comment
# regenerate class
php bin/console make:entity --regenerate App

# alter some files

# Create a Frontend in Vue
cd dungeon-server
composer require nelmio/cors-bundle

# add vue router and axios
cd dungeon-client
npm install vue-router
# install axios as plugin (important, not just install)
vue add axios # = (npm install vue-cli-plugin-axios? + adds plugins/axios.js file)
# if lint error, go to plugins/axios.js, remove options from call 'Plugin.install = function(Vue) {'

# add bootstrap
npm i bootstrap jquery popper.js

# add pagination 
npm install --save vue-paginate-al
# register pagination in main.js

# dungeon-client
# add file dungeon-client/.env

# dungeon-server
# enter routes in dungeon-server/config/packages/routes.yaml
# add controllers 
# add repositories
# adjust usages use Doctrine\Persistence\ManagerRegistry;

# dungeon-client: add styleguidist
vue add styleguidist
# update styleguidist.config.js
module.exports = {
	// set your styleguidist configuration here
	title: 'Adventurous Style Guidist!',
	// components: 'src/components/**/[A-Z]*.vue',
	// defaultExample: true,
	// sections: [
	//   {
	//     name: 'First Section',
	//     components: 'src/components/**/[A-Z]*.vue'
	//   }
	// ],
	// webpackConfig: {
	//   // custom config goes here
	// },
	// components: 'components/**/[a-zA-Z]*.vue',
	exampleMode: 'expand',
	components: 'src/components/global/[a-zA-Z]*.vue',
}

~~~
## based on

* <https://gist.github.com/jcavat/2ed51c6371b9b488d6a940ba1049189b>
* <https://developer.okta.com/blog/2018/06/14/php-crud-app-symfony-vue>
* <https://symfony.com/doc/current/doctrine/reverse_engineering.html>

