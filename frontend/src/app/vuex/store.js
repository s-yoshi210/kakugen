import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';
import home from './modules/home';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    errors: []
  },

  getters: {
    errors: state => state.errors
  },

  mutations: {
    setErrors(state, errors) {
      state.errors = errors;
    }
  },

  actions: {

  },

  modules: {
    auth,
    home
  }
});
