import axios from 'axios';

export default {
  namespaced: true,

  state: {},

  getters: {},

  mutations: {},

  actions: {
    sendCommentRequest({ commit }, data) {
      commit("setErrors", {}, { root: true });

      if (data.type == 'store') {
        return axios
          .post(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + data.kakugenId + '/comment', data)
          .then(response => {
            let data = response.data;
            console.log(data);
          });
      } else {
        return axios
          .put(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + data.kakugenId + '/comment', data)
          .then(response => {
            let data = response.data;
            console.log(data);
          });
      }
    },

    deleteCommentRequest({ commit }, data) {
      commit("setErrors", {}, { root: true });

      return axios
        .delete(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + data.kakugenId + '/comment', data)
        .then(response => {
          console.log('deleteCommentRequest');
          let data = response.data;
          console.log(data);
        });
    }
  },

}
