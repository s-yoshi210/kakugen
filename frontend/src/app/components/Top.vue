<template>
  <div class="top card" style="border: none;">
    <div class="row g-0">
      <div class="col-md-5">
        <img :src="topImg" alt="" class="img-fluid">
      </div>
      <div class="col-md-7">
        <div class="row">
          <div class="col-md">
            <img :src="symbolImg" alt="" class="symbol-img">
            <h2>あなただけの名言が、<br>ここに</h2>
            <h4>名言アプリをはじめてみよう</h4>
            <div class="d-grid gap-2 col-4 mx-auto pt-3">
              <b-link to="/register" class="btn btn-primary py-2 mb-3">アカウント作成</b-link>
              <b-link to="/login" class="btn btn-outline-primary py-2 mb-3">ログイン</b-link>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-md">
            <p><small>Copyright &copy;2021 Yoshitaka Shimazu, All Rights Reserved.</small></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Top",
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
