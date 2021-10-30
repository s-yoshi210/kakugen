<template>
  <div>
    <main class="mt-5">
      <div class="container-fluid">
        <div class="row">
          <div v-for="mykakugen in mykakugens" :key="mykakugen.id" class="mb-3">
            <b-card header-tag="header" footer-tag="footer">
              <template #header>
                <h6 class="mb-0">{{ mykakugen.kakugen.content }}</h6>
                <b-link href="#">{{ mykakugen.kakugen.person_name }}</b-link>
              </template>
              <b-card-text>
                <p>{{ mykakugen.comment }}</p>
              </b-card-text>
              <template #footer>
                <div v-if="mykakugen.favorite">
                  <span @click="unfavorite(mykakugen)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545" class="bi bi-heart-fill" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                    </svg>
                    お気に入り解除
                  </span>
                </div>
                <div v-else>
                  <span @click="favorite(mykakugen)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                      <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                    </svg>
                    お気に入り
                  </span>
                </div>
                <div v-if="mykakugen.comment">
                  <b-button v-b-modal="'modal-' + mykakugen.kakugen_id" @click="showUpdateModal(mykakugen)">コメント修正</b-button>
                </div>
                <div v-else>
                  <b-button v-b-modal="'modal-' + mykakugen.kakugen_id" @click="showStoreModal(mykakugen)">コメント登録</b-button>
                </div>
              </template>
            </b-card>
          </div>
        </div>

        <b-modal
          :id="'modal-' + details.kakugenId"
          title="コメント"
          @ok="saveComment"
          ok-title="保存"
          @cancel="deleteComment"
          cancel-title="削除"
          @hide="closeComment"
        >
          <p>{{ details.content }}</p>
          <p>{{ details.person_name }}</p>
          <textarea
            name=""
            id="comment"
            cols="50" rows="10"
            v-model="details.comment"
          ></textarea>
        </b-modal>

      </div>
    </main>
  </div>
</template>

<script>
  import axios from 'axios';
  import {mapActions} from "vuex";

  export default {
      name: "MyKakugen",

      data() {
        return {
          mykakugens: [],
          details: {
            type: null,
            kakugenID: null,
            content: null,
            person_name: null,
            comment: null
          }
        }
      },

      methods: {
        ...mapActions("home", ["sendCommentRequest", "deleteCommentRequest"]),

        getMyKakugens() {
          axios
            .get(process.env.VUE_APP_API_BASE_URL + 'mykakugens')
            .then(response => {
              this.mykakugens = response.data;
            })
        },
        favorite(kakugen) {
          axios
            .post(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + kakugen.kakugen_id + '/favorite')
            .then(() => {
              kakugen.favorite = true;
            })
        },
        unfavorite(kakugen) {
          axios
            .delete(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + kakugen.kakugen_id + '/favorite')
            .then(() => {
              kakugen.favorite = false;
            })
        },
        showStoreModal(mykakugen) {
          this.setDetails(mykakugen);
          this.details.type = 'store';
        },
        showUpdateModal(mykakugen) {
          this.setDetails(mykakugen);
          this.details.type = 'update';
        },
        saveComment: function(modalEvent) {
          this.details.kakugenId = modalEvent.componentId.slice(6);
          this.sendCommentRequest(this.details)
            .then(() => {
              this.clearDetails();
            })
            .catch(() => {
              modalEvent.preventDefault();
            });
        },
        deleteComment: function (modalEvent) {
          this.details.kakugenId = modalEvent.componentId.slice(6);
          this.deleteCommentRequest(this.details)
            .then(() => {
              this.clearDetails();
            })
        },
        closeComment: function () {
          this.clearDetails();
        },
        setDetails: function (mykakugen) {
          this.details.kakugenId = mykakugen.kakugen_id;
          this.details.content = mykakugen.kakugen.content;
          this.details.person_name = mykakugen.kakugen.person_name;
          this.details.comment = mykakugen.comment;
        },
        clearDetails: function () {
          this.details.type = null;
          this.details.kakugenId = null;
          this.details.comment = null;
          this.details.person_name = null;
          this.details.comment = null;
        }
      },

      mounted() {
        this.getMyKakugens();
      }
  }
</script>

<style scoped>

</style>
