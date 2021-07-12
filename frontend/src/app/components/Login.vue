<template>
  <div>
    <main class="form-signin">
      <form action="">
        <img :src="symbolImg" alt="" class="symbol-img mb-4">
        <h2>ログイン</h2>
        <label for="inputEmail" class="visually-hidden">メールアドレス</label>
        <input type="email" id="inputEmail" class="form-control mb-3" placeholder="メールアドレス" required autofocus>
        <label for="inputPassword" class="visually-hidden">パスワード</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="パスワード" required>
        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">ログイン</button>
        <b-link to="">パスワードをお忘れですか？</b-link>
        <br>
        <b-link to="/register">アカウント作成</b-link>
      </form>
    </main>
  </div>
</template>

<script>
    export default {
      name: "Login",
      data() {
        return {
          symbolImg: require('../assets/img/symbol.png')
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
