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
      }
    },

    mounted() {
      this.getKakugens();
    }
  }
</script>

<style scoped>

</style>
