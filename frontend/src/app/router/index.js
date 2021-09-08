import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'
// import Top from '../components/Top'
// import Login from '../components/auth/Login'

Vue.use(VueRouter)
Vue.use(BootstrapVue)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

const routes = [
  {
    path: '/',
    name: 'Top',
    component: () => import(/* webpackChunkName: "top" */ '../components/Top')
  },
  {
    path: '/home',
    name: 'Home',
    component: () => import(/* webpackChunkName: "home" */ '../components/Home')
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import(/* webpackChunkName: "register" */ '../components/auth/Register')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import(/* webpackChunkName: "login" */ '../components/auth/Login')
  }
];

const router = new VueRouter({
  mode: 'history',
  // base: process.env.BASE_URL
  base: '/',
  routes
});

export default router
