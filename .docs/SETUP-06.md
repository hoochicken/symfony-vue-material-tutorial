## 6. Add Material Framework

Material is a might, mighty framework for building apps.  
Might turn out handy ... let's go!

### 6.1 Before ... 

Material uses sass, so we install the appropriate loaders. 

~~~cli
npm install node-sass sass-loader --save-dev
~~~

### 6.2 Material Girl!

Let's see, how mighty it is and install it. 

~~~cli
npm install vue-material --save
~~~

Afterwards we need to register Material in `dungeon-client/src/main.js` like this:

~~~js
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

///[...]
Vue.use(VueMaterial);
~~~

So the complete file like something like this now:

~~~js
import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import axios from './plugins/axios'

import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

import Demonic from './components/demo/Demonic';

Vue.config.productionTip = false;
Vue.use(VueRouter, axios);
Vue.use(VueMaterial);
Vue.axios.defaults.baseURL = `http://${process.env.VUE_APP_ENV_HOST}:${process.env.VUE_APP_ENV_PORT}`;

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

Restart client with `npm run server` again.

Now on <http://127.0.0.1:8080/demo> you should still see the well-known demonic list. 

### 6.3 Demonic Component with Material

So we just add some Material code do out Demonic file `dungeon-client\src\components\demo\Demonic.vue` like this:

~~~vue
<template>
    <div>
        <h2>Demonic</h2>
        <md-app>
            <md-app-toolbar class="md-primary">
                <span class="md-title">My Title</span>
            </md-app-toolbar>

            <md-app-drawer md-permanent="full">
                <md-toolbar class="md-transparent" md-elevation="0">
                    Navigation
                </md-toolbar>

                <md-list>
                    <md-list-item>
                        <md-icon>move_to_inbox</md-icon>
                        <span class="md-list-item-text">Inbox</span>
                    </md-list-item>

                    <md-list-item>
                        <md-icon>send</md-icon>
                        <span class="md-list-item-text">Sent Mail</span>
                    </md-list-item>

                    <md-list-item>
                        <md-icon>delete</md-icon>
                        <span class="md-list-item-text">Trash</span>
                    </md-list-item>

                    <md-list-item>
                        <md-icon>error</md-icon>
                        <span class="md-list-item-text">Spam</span>
                    </md-list-item>
                </md-list>
            </md-app-drawer>

            <md-app-content>
                <ul>
                    <li v-for="item in demoCracy" :key="item.id">
                        <h4>{{ item.title }}</h4>
                        <div>{{ item.description }}</div>
                    </li>
                </ul>
            </md-app-content>
        </md-app>
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

### Icons not showing ? 

~~~click
cd dungeon-client
npm install material-icons
~~~

Afterwards we need to register out new icons in `dungeon-client/src/main.js` like this:

~~~js
import 'material-icons/iconfont/material-icons.scss';
~~~

# Hello, beauty, you made it!

Yeah, you did it ... now fun-coding can start!!!  

(If you want to, add the styleguistist as well and proceed to seven;-)  
