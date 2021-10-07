<template>
  <div id="app">
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-danger">
        <router-link class="navbar-brand" to="/home">Kakugen</router-link>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarCollapse"
          aria-controls="navbarCollapse"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item" v-show="!user">
              <router-link class="nav-link" to="/">Home</router-link>
            </li>
            <li class="nav-item" v-show="user">
              <router-link class="nav-link" to="/home">Home</router-link>
            </li>
            <li class="nav-item" v-show="!user">
              <router-link class="nav-link" to="/login">ログイン</router-link>
            </li>
            <li class="nav-item" v-show="!user">
              <router-link class="nav-link" to="/register">ユーザ登録</router-link>
            </li>
            <li class="nav-item" v-show="user">
              <a href="#" class="nav-link" @click="logout">ログアウト</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <main role="main" class="container-fluid pt-5">
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
        this.sendLogoutRequest();
        this.$router.push("/");
      }
    }

  }
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
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
</style>
