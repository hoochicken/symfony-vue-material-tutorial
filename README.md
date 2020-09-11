# adventures

## requirements

| Requirement | Source | Note |
| --- | --- | --- |
npm | <https://nodejs.org/en/download/> | 
php | <https://windows.php.net/download/> | php.ini mit folgenden aktivierten Modulen:<br />*extension=pdo_mysql<br />*extension=openssl
composer | <https://getcomposer.org/download/> | 
browser | https://addons.mozilla.org/de/firefox/addon/vue-js-devtools/?src=search |

## kick-starting the project

* get repository from <https://github.com/hoochicken/dungeon.git>

## running the project

```
# start vue, awaits you on http://127.0.0.1:8080/ 
cd adv-client
npm run serve

# start backend, awaits you on http://127.0.0.1:8000/
cd adv-server
php -S 127.0.0.1:8000 -t public

# start dockers for mysql
docker-compose up
# phpmyadmin exposed to 8081, so: http://127.0.0.1:8081/

# styleguidist
cd adv-client
npm run styleguide
# styleguidist expostd to 6060, so: http://localhost:6060/
```

## endpoints

| Type | Name | Url |
| --- | --- | --- |
Frontend | vue | <http://127.0.0.1:8080/>
Backend | Symfony | <http://127.0.0.1:8000/>
Database | phpMyAdmin | <http://127.0.0.1:8081/>

## Renew Entity classes by doctrine

Doctrine uses a php fil, it is called via cli.

A. Doctrine maps existing database

~~~
cd adv-server

# generate php classes
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity

# add getter/setter methods
php bin/console make:entity --regenerate App
# this should generate repository a well, but doesn't, so:

# got to entity file
# add "* @ORM\Entity(repositoryClass="App\Repository\ActionRepository")" within class comment
# regenerate class
php bin/console make:entity --regenerate App
~~~

B. Generate New Entity By Doctrine

~~~
cd adv-server

# create entity
php bin/console make:entity
# follow instructions given by doctrine

# create entity
php bin/console make:migration

# create table in database
php bin/console doctrine:migrations:migrate
~~~ 

## Trouble shooting

* npm rebuild node-sass
* yarn cleanup

## based on

* <https://gist.github.com/jcavat/2ed51c6371b9b488d6a940ba1049189b>
* <https://developer.okta.com/blog/2018/06/14/php-crud-app-symfony-vue>
* <https://symfony.com/doc/current/doctrine/reverse_engineering.html>

## Right From The Start

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
