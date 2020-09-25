## 4. Vue frontend

The client site display of our data is pretty minimal, close to useless.  
C'mon, let's add a beautiful vue frontend.  

### 4.1 Install Vue and Create Project

~~~
npm i -g @vue/cli
~~~

That might take sometime. But as soon, as vue.js is on, you can create a new project.  
We will put the frontend into the ´dungeon-client` folder. 

~~~
vue create dungeon-client 
# follow instructions with default settings
~~~

Now check out: <http://127.0.0.1:8080/>, here we got, you should see Vue.js App now.

### 4.2 Add router 

We need to do: 

* project: add router in vue
* main.js: add VueRouter to app
* App.js: Add `<router-view></router-view>` tag

**Add router in vue**

~~~
# add vue router and axios
cd dungeon-client
npm install vue-router
vue add axios
~~~

Info: What does `vue add axios` do? It does same as `npm install vue-cli-plugin-axios` PLUS adds plugins/axios.js file, pretty useful, nh?

**main.js: add VueRouter to app**

The router need to be added manually to the `main.js`.  

You need to add some lines. Your `dungeon-client/src/main.js` file should look like this now:

~~~js
import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'

Vue.config.productionTip = false;
Vue.use(VueRouter);

const routes = [
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

**App.js: Add <router-view></router-view> tag**

Now you need to use the router in your template file, which is `dungeon-server/src/App.js`: 

~~~vue
<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: 'App',
  components: {

  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
~~~

Now check out: <http://127.0.0.1:8080/>. You should see the Vue.js cli homepage again:-)

Info: What das `<router-view></router-view>`do? It the exit point of your components, a mirror to reflect your work.

### 4.3 Add new route

We need to do: 

* project: new vue component 
* main.js: register VueRouter to app

**project: new vue component**

Generate new folder `dungeon-client/src/components/demo/Demonic.vue` and therein place the following content:

~~~vue
<template>
    <div>
        <h2>Demonic</h2>
    </div>
</template>

<script>
    export default {
        name: "Demonic"
    }
</script>

<style scoped>

</style>
~~~

**main.js: register VueRouter to app**

Add a new rout in `dungeon-client/src/main.js`, like this:

~~~vue
const routes = [
  { path: '/demo', component: Demonic },
  { path: '/', component: App }
];
~~~

**Does routing work??**

Check out <http://127.0.0.1:8080/demo>, there you now should see the Demonic page.
