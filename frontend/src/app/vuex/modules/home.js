import axios from 'axios';

export default {
  namespaced: true,

  state: {},

  getters: {},

  mutations: {},

  actions: {
    sendCommentRequest({ commit }, data) {
      commit("setErrors", {}, { root: true });

      // let kakugenId = data.kakugenId;
      console.log("data");
      console.log(data);
      console.log(data.kakugenId);
      return axios
        .post(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + data.kakugenId + '/comment', data)
        .then(response => {
          console.log("コメント登録完了");
          let data = response.data;
          console.log(data);
        });
    }
  },

}
