# dungeons

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
Styleguidist | styleguidist | <http://127.0.0.1:6060/>

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

## based on

* <https://gist.github.com/jcavat/2ed51c6371b9b488d6a940ba1049189b>
* <https://developer.okta.com/blog/2018/06/14/php-crud-app-symfony-vue>
* <https://symfony.com/doc/current/doctrine/reverse_engineering.html>
