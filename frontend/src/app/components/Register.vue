<template>
  <div>
    <main class="form-register">
      <form action="">
        <img :src="symbolImg" alt="" class="symbol-img mb-4">
        <h2>アカウント作成</h2>
        <label for="inputEmail" class="visually-hidden">メールアドレス</label>
        <input type="email" id="inputEmail" class="form-control mb-3" placeholder="メールアドレス" required autofocus>
        <label for="inputPassword" class="visually-hidden">パスワード</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="パスワード" required>
        <small>半角英数・記号：５〜１６文字</small>
        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">登録</button>
      </form>
    </main>
  </div>
</template>

<script>
  export default {
    name: "Register",
    data() {
      return {
        symbolImg: require('../assets/img/symbol.png')
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
