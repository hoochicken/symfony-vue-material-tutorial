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

### 3.5 Retrieve database data via our application

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

