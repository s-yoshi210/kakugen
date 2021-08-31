import axios from 'axios';

export default {
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
  }
};
