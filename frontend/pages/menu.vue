<template>
  <div class="container">
    <div class="menu-wrap flex">
      <div class="menu">
        <div v-show="$store.state.user" class="menu-list">
          <p><nuxt-link to="/"  class="menu-list-item">Home</nuxt-link></p>
          <p><a class="menu-list-item link" @click="logout">Logout</a></p>
          <p><a class="menu-list-item link" @click="goMyPage">Mypage</a></p>
        </div>
        <div v-show="!$store.state.user" class="menu-list">
          <p><nuxt-link to="/" class="menu-list-item">Home</nuxt-link></p>
          <p><nuxt-link to="/auth/register" class="menu-list-item">Registration</nuxt-link></p>
          <p><nuxt-link to="/auth/login"  class="menu-list-item">Login</nuxt-link></p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { getAuth, signOut } from '@firebase/auth';
export default {
  methods: {
    async logout() {
      const auth = getAuth()
      await signOut(auth)
      alert('ログアウトしました')
      this.$store.commit('logout')
      this.$router.replace('/')
    },
    goIndexPage() {
      this.$router.push('/');
    },
    goMyPage() {
      this.$router.push({ path: `/mypage/${this.$store.state.user.uid}` });
    },
  }
}
</script>
<style scoped>
.menu-wrap {
  height: 70%;
  width: 70%;
  padding: 40px;
  margin: 20px auto;
}
.menu-btn {
  width: 40px;
  height: 40px;
  font-weight: bold;
  box-shadow: 2px 2px 5px 0 rgba(0, 0, 0, 0.231);
}
.menu {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  padding-top: 120px;
}
.menu-list-item {
  text-decoration: none;
  font-size: 40px;
  line-height: 80px;
  color: rgb(16, 102, 230);
  cursor: pointer;
}
</style>