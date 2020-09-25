import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
// import axios from './plugins/axios'

import Demonic from './components/demo/Demonic';

Vue.config.productionTip = false;
Vue.use(VueRouter);
// Vue.use(VueRouter, axios);
// Vue.axios.defaults.baseURL = `http://${process.env.VUE_APP_ENV_HOST}:${process.env.VUE_APP_ENV_PORT}`;
// Vue.axios.defaults.baseURL = `http://localhost:8000`;

const routes = [
  { path: '/demo', component: Demonic },
  { path: '/', component: App }
];

const router = new VueRouter({
  mode: 'history',
  routes
});

console.log(Demonic);


new Vue({
  router,
  render: h => h(App),
}).$mount('#app');
