import Vue from 'vue';
import App from './App.vue';
import router from './router';
// import { store } from './vuex/store';
import store from './vuex/store';
import axios from 'axios';

Vue.config.productionTip = false;

// axiosのレスポンスを途中で捕まえる
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response.status == 422) {
      // バリデーションエラー等
      store.commit("setErrors", error.response.data.errors);
    } else if (error.response.status == 401) {
      // 認証エラー

    } else {
      return Promise.reject(error);
    }
  }
);

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
