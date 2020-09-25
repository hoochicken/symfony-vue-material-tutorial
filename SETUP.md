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
So we simply add the following file `docker-compose.yml` in the document route.
It will start two services: 

1. database server (dungeondb) container, and 
2. phpMyAdmin container.
    
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

Call following command in the commandline afterwards:

~~~cli
docker-compose up
~~~

You can access phpMyAdmin on <http://127.0.0.1:8081>.

**Persistent database data?** 

You might have noticed, that data only exists in the docker container.  
All vanished with  the command `docker-compose down`.  
Pretty usedless, uh?  
So notice the following lines for the database server:

~~~
- ./dump:/docker-entrypoint-initdb.d
~~~

This is a mapping; it will copy every file stored in our `dump` folder into the `docker-entrypoint-initdb.d` folder of the docker container.  
To create persistent data, do as follows:

* Generate an sql dump (via phpMyAdmin)
* Place the sql dump in the `dump` folder

On every docker-compose up, this sql dump will be executed. 

### 3.2 Add symfony stuff for databases

~~~
# add symfony stuff
composer require sensio/framework-extra-bundle
composer require symfony/orm-pack
~~~
You might find a file like `dungeon-server/.env` now in your structure.  
Adjust the parameter DATABASE_URL as follows:

~~~ 
# DATABASE_URL=mysql://<user>:<passwort>@<host>:<port>/<db-name-in-docker-compose> 
DATABASE_URL=mysql://root:root@localhost:3306/dungeondb
~~~

### 3.2 Create table via cli

We want to habe a table wird the following columnd:

| column | type | length | nullable | note |
| --- | --- | --- | --- | --- |
| id | int | - | - | *exists by default, no generation needed* |
| title | string | 255 | nullable | - |
| description | string | 255 | yes | - |
| state | smallint | - | no | - |

~~~cli
# generate new table
php bin/console make:entity

########## insert 1: table name

# Class name of the entity to create or update (e.g. TinyKangaroo):
# > demo
# <= I type here 'demo', for we need a demo table

########## insert 2: columns

# New property name (press <return> to stop adding fields):
# > title

# Field type (enter ? to see all types) [string]:
# >

# Field length [255]:
# >

# Can this field be null in the database (nullable) (yes/no) [no]:
# > no
~~~

Repeat this for the remaining columns, see table above.

Result: we have a wonderful table ... in the pipe, NOT yet there.  
In fact doctrine did the first step: generating a php file  

~~~cli
# actually generating table 'demo' in database
php bin/console make:migration
~~~

To really generate the table, doctrine needs to be told, to actually do so with:

~~~cli
# actually generating table 'demo' in database using doctrine
php bin/console doctrine:migrations:migrate
~~~
  
You might want to have a peek into the phpMyAdmin to admire our work.  
Go to <http://127.0.0.1:8081>

### 3.3 What happened anyway?

What have we done so far? A LOT OF thingsies. And more: Doctrine helped us pretty much.  

**What we gave to doctrine**

* a database (docker-compose.yml)

**What doctrine did for us**

* database side
    * handling connection to database (dungeon-server/.env, DATABASE_URL)
    * generation of table `demo` due to our requirements (via cli)
    * generation of a migration script (dungeon-server/migrations/Version000000000.php)
    * generation of table `demo` by execution of a migration script (s. phpMyAdmin:-)
* file side - generation
   * migration script (dungeon-server/migrations/Version000000000.php)
   * dungeon-server/src/Entity/DemoEntity.php 
   * dungeon-server/src/Repository/DemoRepository.php
   
**What we need to do**

* Generate a new entry as test entry in our `demo` database
* Using entity and repository; adjusting the latter to our needs, so let's proceed! 

## 3.4 Generate test entry in `demo` table

We would love to have some displayable data. So let's add a sample entry:  

* Go to your phpMyAdmin, whom you will find here: <http://127.0.0.1:8081>.
* Login with 
    * user: root
    * passwort: root
* navigate to `demo` table and generate new entry.

Lazy? Use the following SQL and execute it:

~~~sql
INSERT INTO `demo` (`title`, `description`, `state`) VALUES ('Custom Title', 'Custom Description', '1');
~~~  

## 3.5 Retrieve database data via our application

Our `DemoRepository` in `dungeon-server\src\Repository\DemoRepository.php` needs to be new methods. 
Say, we need to turn the `Demo` object into a generally usable array.  
Therefore we generate a transform() method ... and a transformAll() method, for a whole bunch (array) of objects.  
So we add the following code a methods:     

~~~php
<?php
    public function transform(Demo $demo)
    {
        return [
            'id'    => (int) $demo->getId(),
            'title' => (string) $demo->getTitle(),
            'description' => (string) $demo->getDescription(),
            'state' => (int) $demo->getState()
        ];
    }

    public function transformAll()
    {
        $demoEntry = $this->findAll();
        $return = [];

        foreach ($demoEntry as $demoSingle) {
            $return[] = $this->transform($demoSingle);
        }

        return $return;
    }
?>
~~~

And then we actually use these new methods in our `dungeon-server/src/Controller/DemoController.php`: 

~~~php
<?php
namespace App\Controller;

use App\Repository\DemoRepository;

class DemoController extends ApiController
{
    public function demonicAction(DemoRepository $demoRepository)
    {
        // retrieve demo entries as array
        $demos = $demoRepository->transformAll();
        return $this->respond($demos);
    }
}
~~~

Now let's check, if everything is working as ist should.  
Call the url <http://127.0.0.1:8000/demo>. Here you should see something like this:

~~~json
[{"id":1,"title":"Custom Title","description":"Custom Description","state":1}]
~~~

**Congratulations! You made it. First mission goal achieved!**

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

