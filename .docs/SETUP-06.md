## 6. Add Material Framework

Material is a might, mighty framework for building apps.  
Let's see, how mighty it is and install it. 

~~~cli
npm install vue-material --save
~~~





## TODO 

~~~
# Create the Symfony Skeleton API

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



## 5. Advanced

### 5.1 Uh...m ... One to go: Persistent database data** 

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

### 5.2 Renew Entity classes by doctrine

You start your tables by doctrine, then you make some alteration in phpMyAdmin, most of all: add columns.  
Yet doctrine stays the way it is ... unless you politely ask it to adjust to the existing tables,

Go to command line and to as follows:

~~~
cd adv-server

# generate php classes
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity

# add getter/setter methods
php bin/console make:entity --regenerate App
# this should generate repository a well, but doesn't, so:

# got to entity file
# if the line not already exists, add "* @ORM\Entity(repositoryClass="App\Repository\ActionRepository")" within class comment
# regenerate class
php bin/console make:entity --regenerate App
~~~

Thus you nudge doctrine to add new properties, and methods (getter, setter).

Tip: Always generate tables/entities within cli using doctrine.     
Generate the alterations (new columns etc.) within noble phpMyAdmin.

Hark! Hark! the lark: Remember to also adjust you sql dump file(s), s. 5.1  

## 6. Based on following Tutorials And Whatevers

* <https://gist.github.com/jcavat/2ed51c6371b9b488d6a940ba1049189b>
* <https://developer.okta.com/blog/2018/06/14/php-crud-app-symfony-vue>
* <https://symfony.com/doc/current/doctrine/reverse_engineering.html>
* <https://router.vuejs.org/installation.html#direct-download-cdn>