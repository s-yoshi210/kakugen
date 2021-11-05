<template>
  <div>
    <main>
      <div class="container-fluid">
        <h2>My名言</h2>
        <div class="row">
          <div v-for="mykakugen in mykakugens" :key="mykakugen.id" class="mb-3" align="center">
            <b-card header-tag="header" footer-tag="footer" style="max-width: 50rem;">
              <template #header>
                <div class="row">
                  <div class="col-12 col-md-8">
                    <h6 class="mb-0 text-start py-3 fw-bold">{{ mykakugen.kakugen.content }}</h6>
                  </div>
                  <div class="col-12 col-md-4 position-relative pt-3">
                    <b-link href="#" @click="openPersonModal(mykakugen)" class="position-absolute bottom-0 end-0">{{ mykakugen.kakugen.person_name }}</b-link>
                  </div>
                </div>
              </template>
              <b-card-text class="text-start">
                <p>{{ mykakugen.comment }}</p>
              </b-card-text>
              <template #footer>
                <div class="row justify-content-center py-1">
                  <div class="col-12 col-md-4 py-1">
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
                  </div>
                  <div class="col-12 col-md-4 py-1">
                    <div v-if="!mykakugen.comment" v-b-modal="'modal-' + mykakugen.kakugen_id" @click="showUpdateModal(mykakugen)">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-dots" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                        <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                      </svg>
                      コメント登録
                    </div>
                    <div v-else v-b-modal="'modal-' + mykakugen.kakugen_id" @click="showStoreModal(mykakugen)">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-left-dots-fill" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793V2zm5 4a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                      </svg>
                      コメント編集
                    </div>
                  </div>
                </div>
              </template>
            </b-card>
          </div>
        </div>
        <!-- コメント編集モーダル -->
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
        <!-- 人物詳細モーダル -->
        <b-modal
          id="person-modal"
          :title="details.person_name"
          ok-only
          ok-variant="secondary"
          ok-title="Close"
        >
          <p class="my-4">{{ person.content }}</p>
        </b-modal>
      </div>
    </main>
  </div>
</template>

<script>
  import axios from 'axios';
  import { mapActions } from "vuex";

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
          },
          person: {
            content: null
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
        },
        openPersonModal(mykakugen) {
          this.details.person_name = mykakugen.kakugen.person_name;
          axios
            .get(process.env.VUE_APP_API_BASE_URL + 'person', {
              params: {
                person_name: mykakugen.kakugen.person_name
              }
            })
            .then(response => {
              this.person.content = response.data;
              this.$bvModal.show('person-modal');
            })
        }
      },

      mounted() {
        this.getMyKakugens();
      }
  }
</script>

<style scoped>

</style>
