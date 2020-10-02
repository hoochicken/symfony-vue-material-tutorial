## 7. Advanced stuff 

### 7.1 Styleguidist 

~~~cli
# dungeon-client: add styleguidist
cd dungeon-client
vue add styleguidist
~~~

Add one line to `styleguidist.config.js`, it is: tada! `components: 'src/components/global/[a-zA-Z]*.vue'`  

~~~js
module.exports = {
	// set your styleguidist configuration here
	title: 'Dungeoneer\'s Style Guidist!',
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
	components: 'src/components/global/[a-zA-Z]*.vue'
}
~~~

~~~cli
# dungeon-client: add styleguidist
cd dungeon-client
npm run styleguide
~~~

## 7.2 Uh...m ... One to go: Persistent database data** 

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

## 7.3 Renew Entity classes by doctrine

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

## 8. Based on following Tutorials And Whatevers

* <https://gist.github.com/jcavat/2ed51c6371b9b488d6a940ba1049189b>
* <https://developer.okta.com/blog/2018/06/14/php-crud-app-symfony-vue>
* <https://symfony.com/doc/current/doctrine/reverse_engineering.html>
* <https://router.vuejs.org/installation.html#direct-download-cdn>
