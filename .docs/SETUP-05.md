## 5. Glue symfony and vue by axios 

Until now, the frontend works (vue.js) and the backend (symfony-skeleton) are working properly, but seperately.  
Now we'r gonna glue them together with an Ajax plugin called `axios`.

### 5.1 Add axios

~~~cli
vue add axios
~~~

Info: What das `vue add axios` actually do?  It DOes two things:

* add axios as plufin in vue.js cli
* register axios component in main.js 

Further on you should add following line to `dungeon-client/src/main.js: 

~~~vue
Vue.axios.defaults.baseURL = `http://localhost:8000`;
~~~

This line points as you can see to the symfony endpoint for axios, where the data is retrieved from. 

Your `dungeon-client/src/main.js` should look like this now:

~~~vue
import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import axios from './plugins/axios'

import Demonic from './components/demo/Demonic';

Vue.config.productionTip = false;
Vue.use(VueRouter, axios);
Vue.axios.defaults.baseURL = `http://localhost:8000`;

const routes = [
  { path: '/demo', component: Demonic },
  { path: '/', component: App }
];

const router = new VueRouter({
  mode: 'history',
  routes
});

new Vue({
  router,
  render: h => h(App),
}).$mount('#app');
~~~

If you get an error like this: `'options' is defined but never used`; you need to fix this. 
Go to `dungeon-client/src/plugins/axios.js` and strip "options" from install call.   
`Plugin.install = function(Vue, options) {` => `Plugin.install = function(Vue) {` 

### 5.2 Use axios in component

Now let's add the axios call in `dungeon-client/src/demo/Demonic.vue`

~~~vue
<template>
    <div>
        <h2>Demonic</h2>
        {{ demoCracy }}
    </div>
</template>

<script>
    export default {
        name: "Demonic",
        data() {
            return {
                demoCracy: {}
            }
        },
        async mounted() {
            var params =  { };
            const response = await this.axios.post('/demo', params);
            this.demoCracy = response.data;
        }
    }
</script>

<style scoped>

</style>
~~~

### 5.3 Use .env file defining constants



## 4.4 Add Material Framework

npm install vue-material --save






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
