<template>
  <div>
    <main class="home mt-md-5 mb-5">
      <div class="container-fluid">
        <h2>{{ currentDate() }}</h2>
        <div class="row mt-md-5">
          <div v-for="(kakugen, index) in kakugens" :key="kakugen.id" class="col-md-4">
            <div class="card my-3">
              <div class="card-header">
                今日の名言 {{ index + 1 }}
              </div>
              <div class="card-body">
                <h5 class="card-title mb-4 fw-bold">{{ kakugen.content }}</h5>
                <p class="card-text">{{ kakugen.person_name }}</p>
              </div>
              <div class="row justify-content-center py-md-3">
                <div class="col-12 col-md-6 py-2">
                  <div v-if="kakugen.favorite">
                    <span
                      @click="unfavorite(kakugen)"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545" class="bi bi-heart-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                      </svg>
                      お気に入り解除
                    </span>
                  </div>
                  <div v-else>
                    <span
                      @click="favorite(kakugen)"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                      </svg>
                      お気に入り
                    </span>
                  </div>
                </div>
                <div class="col-12 col-md-6 py-2">
                  <div v-if="!kakugen.comment" v-b-modal="'modal-' + kakugen.id" @click="openCommentModal(kakugen)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-dots" viewBox="0 0 16 16">
                      <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                      <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    コメント登録
                  </div>
                  <div v-if="kakugen.comment" v-b-modal="'modal-' + kakugen.id" @click="openCommentModal(kakugen)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-dots-fill" viewBox="0 0 16 16">
                      <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    コメント編集
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- コメントモーダル -->
          <b-modal
            :id="'modal-' + details.kakugenId"
            title="コメント"
            @ok="saveComment"
            ok-title="保存"
            @cancel="clearCommentModal"
            cancel-title="キャンセル"
            no-close-on-backdrop
            no-close-on-esc
            hide-header-close
          >
            <p>{{ details.content }}</p>
            <p>{{ details.person_name }}</p>
            <textarea
              name=""
              id="comment"
              cols="50" rows="10"
              v-model="details.comment"
              :class="{ 'is-invalid': errors.comment}"
            ></textarea>
            <div class="invalid-feedback" v-if="errors.comment">
              <p v-for="error in errors.comment" :key="error">{{ error }}</p>
            </div>
          </b-modal>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
  import axios from 'axios';
  import moment from 'moment';
  import { mapGetters, mapActions } from 'vuex';

  export default {
    name: "Home",

    data() {
      return {
        kakugens: [],
        details: {
          kakugenId: null,
          content: null,
          personName: null,
          comment: null
        }
      }
    },

    computed: {
      ...mapGetters(["errors"])
    },

    methods: {
      ...mapActions("home", ["sendCommentRequest"]),

      currentDate() {
        moment.locale('ja');
        return moment().format('YYYY年M月D日(dd)')
      },
      // @TODO:必要なら処理を１つのファイルにまとめる
      getKakugens() {
        axios
          .get(process.env.VUE_APP_API_BASE_URL + 'kakugens')
          .then(response => {
            this.kakugens = response.data;
          })
      },
      favorite(kakugen) {
        axios
          .post(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + kakugen.id + '/favorite')
          .then(() => {
            kakugen.favorite = true;
          })
      },
      unfavorite(kakugen) {
        axios
          .delete(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + kakugen.id + '/favorite')
          .then(() => {
            kakugen.favorite = false;
          })
      },
      openCommentModal(kakugen) {
        this.details.kakugenId = kakugen.id;
        this.details.content = kakugen.content;
        this.details.personName = kakugen.person_name;
        this.details.comment = kakugen.comment;
      },
      saveComment: function(modalEvent) {
        this.details.kakugenId = modalEvent.componentId.slice(6);
        this.sendCommentRequest(this.details)
          .then(() => {
            this.clearCommentModal();
          })
          .catch(() => {
            modalEvent.preventDefault();
          });
      },
      clearCommentModal: function () {
        this.details.kakugenId = null;
        this.details.content = null;
        this.details.personName = null;
        this.details.comment = null;
      }
    },

    mounted() {
      this.$store.commit('setErrors', {});
      this.getKakugens();
    }
  }
</script>

<style scoped>

</style>
