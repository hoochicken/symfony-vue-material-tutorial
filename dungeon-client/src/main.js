import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import axios from './plugins/axios'
import Pagination from 'vue-paginate-al'

import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

import Demonic from './components/demo/Demonic';
Vue.config.productionTip = false;
Vue.use(VueRouter, axios);
Vue.use(VueMaterial);
Vue.component('pagination', Pagination);

Vue.axios.defaults.baseURL = `http://${process.env.VUE_APP_ENV_HOST}:${process.env.VUE_APP_ENV_PORT}`;

const routes = [
  { path: '/demo', component: Demonic },
  { path: '/app', component: App },
  { path: '/', component: App },
];

const router = new VueRouter({
  mode: 'history',
  routes
});

new Vue({
  router,
  render: h => h(App),
}).$mount('#app');
