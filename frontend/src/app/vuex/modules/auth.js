import axios from 'axios';

export default {
  namespaced: true,

  state: {
    userData: null
  },

  getters: {
    user: state => state.userData
  },

  mutations: {
    setUserData(state, user) {
      state.userData = user;
    }
  },

  actions: {
    getUserData({ commit }) {
      axios
        .get(process.env.VUE_APP_API_BASE_URL + 'user')
        .then(response => {
          commit("setUserData", response.data);
        })
        .catch(() => {
          localStorage.removeItem("authToken");
        })
    },
    sendLoginRequest({ commit }, data) {
      commit("setErrors", {}, { root: true });
      return axios
        .post(process.env.VUE_APP_API_BASE_URL + 'login', data)
        .then(response => {
          // 成功後
          commit("setUserData", response.data.user);
          localStorage.setItem("authToken", response.data.token);
        });
    },
    sendRegisterRequest({ commit }, data) {
      commit("setErrors", {}, { root: true });
      return axios
        .post(process.env.VUE_APP_API_BASE_URL + 'register', data)
        .then(response => {
          commit("setUserData", response.data.user);
          // アクセストークン保存 Localstorage or Cookie
          localStorage.setItem("authToken", response.data.token);
        });
    },
    sendLogoutRequest({ commit }) {
      axios.post(process.env.VUE_APP_API_BASE_URL + 'logout').then(() => {
        commit("setUserData", null);
        localStorage.removeItem("authToken");
      })
    }
  }
};
