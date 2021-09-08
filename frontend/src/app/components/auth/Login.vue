<template>
  <div>
    <main class="form-signin">
      <form @submit.prevent="login" method="post">
<!--        <img :src="symbolImg" alt="" class="symbol-img mb-4">-->
        <h2>ログイン</h2>
        <div class="form-group">
          <label for="inputEmail" class="visually-hidden">メールアドレス</label>
          <input
            type="email"
            id="inputEmail"
            class="form-control mb-3"
            :class="{ 'is-invalid': errors.email }"
            placeholder="メールアドレス"
            required autofocus
            v-model="details.email"
          >
          <div class="invalid-feedback" v-if="errors.email">
            <p v-for="error in errors.email" :key="error">{{ error }}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword" class="visually-hidden">パスワード</label>
          <input
            type="password"
            id="inputPassword"
            class="form-control"
            :class="{ 'is-invalid': errors.password }"
            placeholder="パスワード"
            required
            v-model="details.password"
          >
          <div class="invalid-feedback" v-if="errors.password">
            <p v-for="error in errors.password" :key="error">{{ error }}</p>
          </div>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">ログイン</button>
      </form>
      <b-link to="">パスワードをお忘れですか？</b-link>
      <br>
      <b-link to="/register">アカウント作成</b-link>
    </main>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  export default {
    name: "Login",

    data() {
      return {
        details: {
          email: null,
          password: null
        }
      }
    },

    computed: {
      ...mapGetters(["errors"])
    },

    mounted() {
      this.$store.commit('setErrors', {});
    },

    methods: {
      ...mapActions("auth", ["sendLoginRequest"]),

      login: function () {
        this.sendLoginRequest(this.details).then(() => {
          this.$router.push({ name: "Home" });
        });
      }
    }
  }
</script>

<style scoped>
  .form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
  }
  .symbol-img {
    width: 72px;
    height: 57px;
  }
</style>
