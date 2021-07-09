<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <HelloWorld msg="Welcome to Your Vue.js App" :ecosystems="ecosystems" />
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/app/components/HelloWorld.vue'
import axios from 'axios'

export default {
  name: 'Home',
  components: {
    HelloWorld
  },
  data() {
    return {
      ecosystems: []
    }
  },
  async mounted() {
    const res = await axios.get("/api/ecosystems");
    // 子コンポーネントが扱いやすい形に整形しときます
    const ecosystems = Object.keys(res.data).map(key => {
      return {
        name: key,
        link: res.data[key]
      };
    });
    this.ecosystems = ecosystems;
  }
}
</script>
