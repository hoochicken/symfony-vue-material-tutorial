import Vue from 'vue'
import App from './App.vue'
import axios from './plugins/axios'
import VueRouter from 'vue-router'

Vue.config.productionTip = false;
Vue.use(VueRouter, axios);
Vue.axios.defaults.baseURL = `http://${process.env.VUE_APP_ENV_HOST}:${process.env.VUE_APP_ENV_PORT}`;

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
