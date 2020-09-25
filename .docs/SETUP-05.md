## 5. Glue symfony and vue by axios 

Until now, the frontend works (vue.js) and the backend (symfony-skeleton) are working properly, but separately.  
Now we'r gonna glue them together with an Ajax plugin called `axios`.

### 5.1 Enable Cross Site

When backend and frontend come together, we must ship around a common misunderstanding.  
We must teach the server Cross site scripting, so we install nelmio cors bundle right away:-)

~~~cli
cd dungeon-server
composer require nelmio/cors-bundle
~~~

### 5.2 Add axios

Now we install `axios` on client-site. 

~~~cli
cd dungeon-client
vue add axios
~~~

Info: What das `vue add axios` actually do?  It does two things:

* add axios as plufin in vue.js cli
* register axios component in main.js 

Further on you should add following line to `dungeon-client/src/main.js`: 

~~~vuejs
Vue.axios.defaults.baseURL = `http://localhost:8000`;
~~~

This line points as you can see to the symfony endpoint for axios, where the data is retrieved from. 

Your `dungeon-client/src/main.js` should look like this now:

~~~vuejs
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

### 5.3 But the data looks like shit!?!!?!

Let's add a `v-for` loop as follows:

Your Template might look like this now:
~~~vue
<template>
    <div>
        <h2>Demonic</h2>
        <ul>
            <li v-for="item in demoCracy" :key="item.id">
                <h4>{{ item.title }}</h4>
                <div>{{ item.description }}</div>
            </li>
        </ul>
    </div>
</template>
~~~

### 5.4 Use .env file defining constants

That hard-coded baseURL in our `dungeon-client/src/main.js` ist pretty ugly, we MUST fix that beauty issue.

So: Add following file to your client# root: `dungeon-client/.env`: 

~~~cli
VUE_APP_ENV_HOST=localhost
VUE_APP_ENV_PORT=8000
~~~

Afterwars strip that hard-coded baseURL from your `dungeon-client/src/main.js`, and replace it with:

~~~vuejs
Vue.axios.defaults.baseURL = `http://${process.env.VUE_APP_ENV_HOST}:${process.env.VUE_APP_ENV_PORT}`;
~~~
