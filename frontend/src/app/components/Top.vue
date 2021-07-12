<template>
  <div>
    <main class="top">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <img :src="topImg" alt="" class="img-fluid" style="height: 750px;">
          </div>
          <div class="col-md-6">
            <img :src="symbolImg" alt="" class="symbol-img">
            <h2>あなただけの名言が、<br>ここに</h2>
            <h4>名言アプリをはじめてみよう</h4>
            <div class="d-grid gap-2 col-4 mx-auto">
              <b-link to="/register" class="btn btn-primary">アカウント作成</b-link>
              <b-link to="/login" class="btn btn-outline-primary">ログイン</b-link>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md">
            <p><small>Copyright &copy;2020 Yoshitaka Shimazu, All Rights Reserved.</small></p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
  export default {
    name: "top",
    data() {
      return {
        topImg: require('../assets/img/top.png'),
        symbolImg: require('../assets/img/symbol.png')
      }
    }
  }
</script>

<style scoped>
.symbol-img {
  padding-top: 60px;
}
h2 {
  padding-top: 45px;
}
h4 {
  padding-top: 80px;
}
</style>
