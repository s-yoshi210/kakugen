<template>
  <div>
    <main class="form-register">
      <form @submit.prevent="register" method="post">
<!--        <img :src="symbolImg" alt="" class="symbol-img mb-4">-->
        <div class="form-group">
          <h2 class="mb-3">アカウント作成</h2>
          <label for="name" class="visually-hidden">ユーザー名</label>
          <input
            type="text"
            id="name"
            class="form-control mb-3"
            :class="{ 'is-invalid': errors.name }"
            placeholder="ユーザー名"
            required autofocus
            maxlength="80"
            v-model="details.name"
          >
          <div class="invalid-feedback" v-if="errors.name">
            <p v-for="error in errors.name" :key="error">{{ error }}</p>
          </div>
        </div>

        <div class="form-group">
          <label for="email" class="visually-hidden">メールアドレス</label>
          <input
            type="email"
            id="email"
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
          <label for="password" class="visually-hidden">パスワード</label>
          <input
            type="password"
            id="password"
            class="form-control"
            :class="{ 'is-invalid': errors.password }"
            placeholder="パスワード"
            required
            maxlength="16"
            v-model="details.password"
          >
          <small>半角英数・記号：8〜16文字</small>
          <div class="invalid-feedback" v-if="errors.password">
            <p v-for="error in errors.password" :key="error">{{ error }}</p>
          </div>
        </div>

        <div class="form-group">
          <label for="password_confirmation" class="visually-hidden">パスワード（確認）</label>
          <input
            type="password"
            id="password_confirmation"
            class="form-control"
            :class="{ 'is-invalid': errors.password }"
            placeholder="パスワード(確認)"
            required
            maxlength="16"
            v-model="details.password_confirmation"
          >
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">登録</button>
      </form>
    </main>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  export default {
    name: "Register",

    data() {
      return {
        details: {
          name: null,
          email: null,
          password: null,
          password_confirmation: null
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
      ...mapActions(["sendRegisterRequest"]),

      register: function() {
        this.sendRegisterRequest(this.details).then(() => {
          this.$router.push({ name: "Home" });
        });
      }
    }
  }
</script>

<style scoped>
.form-register {
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
