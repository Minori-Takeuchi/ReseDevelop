<template>
  <div class="default">
    <header class="flex jc-space-between header">
      <div class="flex header-menu">
        <img src="~/assets/img/reseicon.png" @click="goMenuPage" alt="メニューへ" class="menu-img">
        <h1><nuxt-link to="/" class="menu-ttl">Rese</nuxt-link></h1>
      </div>
      <search v-show="$route.name === 'index'"></search>
    </header>
    <main>
      <Nuxt />
    </main>
    <footer class="flex footer">
      <button v-if="$route.name !== 'menu'" @click="goMenuPage" class="btn menu-btn">メニュー</button>
      <button v-if="$route.name === 'menu'" @click="goIndexPage" class="btn menu-btn">Homeへ戻る</button>
      <button v-show="$store.state.user && $store.state.user.role === 3" @click="goAdminPage" class="btn menu-btn">管理</button>
      <button v-show="$store.state.user && $store.state.user.role === 2" @click="goManagerPage" class="btn menu-btn">店舗作成</button>
    </footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      modalFlag: false
    }
  },
  methods: {
    goMenuPage() {
      this.$router.push("/menu");
    },
    goIndexPage() {
      this.$router.push('/');
    },
    goAdminPage() {
      this.$router.push({ path: `/admin/${this.$store.state.user.uid}` });
    },
    goManagerPage() {
      this.$router.push({ path: `/manager/${this.$store.state.user.uid}` });
    },
  }
}
</script>

<style scoped>
.default {
  width: 100vw;
  height: 100%;
}
/* ヘッダー */
.header {
  position:fixed;
  width: 100%;
  height: 100px;
  align-items: center;
  background: linear-gradient(rgb(232, 234, 238),rgba(232, 234, 238, 0));
}
main {
  padding-top: 100px;
  padding-bottom: 100px;
}
.header-menu {
  margin-left: 50px;
  height: 50px;
  align-items: center;
}
.menu-ttl{
  font-size: 24px;
  margin:5px 0 0 20px;
  height: 30px;
  color: rgb(16, 102, 230);
  text-decoration: none;
}
.link {
  cursor: pointer;
}
.menu-img{
  cursor: pointer;
  width: 35px;
  height: 35px;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
  border-radius: 3px;
}
.menu-btn{
  cursor: pointer;
  width: 100px;
  height: 35px;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
  border-radius: 3px;
}
/* フッター */
.footer {
  position: fixed;
  bottom: 0;
  width: 100%;
  height: 70px;
  align-items: center;
  background: rgb(232, 234, 238);
  justify-content: space-around;
}

@media screen and (max-width:768px) {
  .header-menu {
  margin-left: 20px;
  height: 50px;
  align-items: center;
}
}
</style>