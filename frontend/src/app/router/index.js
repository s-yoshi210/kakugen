import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'
// import Top from '../components/Top'
// import Login from '../components/auth/Login'

Vue.use(VueRouter)
Vue.use(BootstrapVue)

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

const guest = (to, from, next) => {
  if (!localStorage.getItem('authToken')) {
    return next();
  } else {
    return next('/home');
  }
};
// const auth = (to, from, next) => {
//   if (localStorage.getItem('authToken')) {
//     return next();
//   } else {
//     return next('/login');
//   }
// };

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
    beforeEnter: guest,
    component: () => import(/* webpackChunkName: "register" */ '../components/auth/Register')
  },
  {
    path: '/login',
    name: 'Login',
    beforeEnter: guest,
    component: () => import(/* webpackChunkName: "login" */ '../components/auth/Login')
  },
  {
    path: '/mykakugen',
    name: 'MyKakugen',
    component: () => import(/* webpackChunkName: "mykakugen" */ '../components/MyKakugen')
  }
];

const router = new VueRouter({
  mode: 'history',
  // base: process.env.BASE_URL
  base: '/',
  routes
});

export default router
