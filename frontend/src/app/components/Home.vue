<template>
  <div>
    <main class="home mt-5">
      <div class="container-fluid">
        <h2>{{ currentDate() }}</h2>
        <div class="row mt-5">
          <div v-for="(kakugen, index) in kakugens" :key="kakugen.id" class="col-md-4">
            <div class="card">
              <div class="card-header">
                今日の名言 {{ index + 1 }}
              </div>
              <div class="card-body">
                <h5 class="card-title mb-4">{{ kakugen.content }}</h5>
                <p class="card-text">{{ kakugen.person_name }}</p>
              </div>
              <div v-if="kakugen.favorite">
                <span
                  @click="unfavorite(kakugen)"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545" class="bi bi-heart-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                  </svg>
                </span>
              </div>
              <div v-else>
                <span
                  @click="favorite(kakugen)"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                  </svg>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
  import axios from 'axios';
  import moment from 'moment';

  export default {
    name: "Home",

    data() {
      return {
        kakugens: []
      }
    },

    methods: {
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
        .delete(process.env.VUE_APP_API_BASE_URL + 'kakugens/' + kakugen.id + '/unfavorite')
        .then(() => {
          kakugen.favorite = false;
        })
      }

    },

    mounted() {
      this.getKakugens();
    }
  }
</script>

<style scoped>

</style>
