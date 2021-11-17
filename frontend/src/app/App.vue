<template>
  <div id="app" class="mx-auto">
    <div>
      <b-navbar toggleable="md" type="dark" variant="danger" class="fixed-top">
        <b-navbar-brand to="/home" v-show="user">Kakugen</b-navbar-brand>
        <b-navbar-brand to="/" v-show="!user">Kakugen</b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav>
            <b-nav-item to="/home" v-show="user">Home</b-nav-item>
            <b-nav-item to="/mykakugen" v-show="user">My格言</b-nav-item>
            <b-nav-item to="/register" v-show="!user">アカウント作成</b-nav-item>
            <b-nav-item to="/login" v-show="!user">ログイン</b-nav-item>
          </b-navbar-nav>
          <b-navbar-nav class="ml-auto">
            <b-nav-item @click="logout" v-show="user" right>ログアウト</b-nav-item>
          </b-navbar-nav>
        </b-collapse>
      </b-navbar>
    </div>

    <main role="main" class="container-fluid">
      <router-view/>
    </main>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";

  export default {
    name: 'App',

    computed: {
      ...mapGetters("auth", ["user"])
    },

    mounted() {
      if (localStorage.getItem("authToken")) {
        this.getUserData();
      }
    },

    methods: {
      ...mapActions("auth", ["sendLogoutRequest", "getUserData"]),

      logout() {
        this.sendLogoutRequest()
          .then(() => {
            this.$router.push("/");
          })
      }
    }

  }
</script>

<style>
#app {
  font-family: Arial, 游ゴシック体, YuGothic, メイリオ, Meiryo, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}

.ml-auto {
  margin-left: auto!important;
}

main {
  padding-top: 55px;
  padding-left: 0 !important;
  padding-right: 0 !important;
}
</style>
