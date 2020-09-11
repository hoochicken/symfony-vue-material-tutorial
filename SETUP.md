# Dungeons - Setup

How to setup dungesons all by yourself

## requirements

| Requirement | Source | Note |
| --- | --- | --- |
npm | <https://nodejs.org/en/download/> | 
php | <https://windows.php.net/download/> | php.ini mit folgenden aktivierten Modulen:<br />*extension=pdo_mysql<br />*extension=openssl
composer | <https://getcomposer.org/download/> | 
browser | https://addons.mozilla.org/de/firefox/addon/vue-js-devtools/?src=search |

## Init project

create folder of project, e. g. "dungeons"

## Init server

Here we initialise our proud project just by generating a new folder.  
We use the symfony/website-skeleton. 
The server will be available in your browser on http://127.0.0.1:8000
~~~cli
# Building server
# server - 127.0.0.1:8000
composer create-project symfony/website-skeleton dungeon-server

# enter the dungeon-server
cd dungeon-server

# start the server
php -S 127.0.0.1:8000 -t public
~~~

## First Routing Check

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
                'count' => 0
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
[{"title":"The Real Demo","count":0}]
~~~
 
## Extend A Little 

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

use Symfony\Component\Routing\Annotation\Route;

class MovieController extends ApiController
{
    /**
    * @Route("/movies")
    */
    public function moviesAction()
    {
        return $this->respond([
            [
                'title' => 'The Princess Bride',
                'count' => 0
            ]
        ]);
    }
}
~~~ 

## Add symfony/ORM stuff

~~~
# add symfony stuff
composer require sensio/framework-extra-bundle
composer require symfony/orm-pack
~~~

## Add docker stuff for database

~~~
# add file:   docker-compose.yml with phpMyAdmin etc.
# add file:   Dockerfile
# add folder: dump/ 
# add file:   dungeon-server/.env (for doctrine, adjust path of service) 
docker-compose up
~~~


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

