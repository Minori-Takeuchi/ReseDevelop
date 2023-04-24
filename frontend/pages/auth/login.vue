<template>
  <div class="login flex">
    <div class="login-wrap">
      <p class="login-ttl">Login</p>
      <validation-observer ref="obs" v-slot="ObserverProps">
        <!-- メールアドレス -->
        <validation-provider v-slot="ProviderProps" rules="required|max:191|email">
          <div class="login-item">
            <img src="~/assets/img/mail.png" alt="メールアドレス">
            <input type="email" name="メールアドレス" v-model="email" placeholder="email" />
          </div>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <!-- パスワード -->
        <validation-provider v-slot="ProviderProps" rules="required|min:8|max:191">
          <div class="login-item">
            <img src="~/assets/img/password.png" alt="パスワード">
            <input type="password" name="パスワード" v-model="password" placeholder="password"/>
          </div>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <button @click="login" :disabled="ObserverProps.invalid || !ObserverProps.validated" class="login-btn btn">送信</button>
      </validation-observer>
    </div>
    <nuxt-link to="/auth/mailLogin" class="mail-login-link">メールでログインする</nuxt-link>
</div>
</template>

<script>
import { getAuth, signInWithEmailAndPassword } from '@firebase/auth';
export default {
  data() {
    return {
      email: null,
      password: null,
    };
  },
  methods: {
    async login() {
      if (!this.email || !this.password) {
        alert("メールアドレスまたはパスワードが入力されていません。");
        return;
      }
      const auth = getAuth();
      try {

        const userCredential = await signInWithEmailAndPassword(auth, this.email, this.password);
        const { data } = await this.$axios.get("/api/user/" + userCredential.user.uid)
        const user = {
          uid: userCredential.user.uid,
          email: userCredential.user.email,
          name: data.name,
          role: data.role,
        };
        this.$router.push("/");
        alert('ログインしました')
        this.$store.dispatch('loginAction', user)
      } catch (error) {
        alert("メールアドレスまたはパスワードが間違っています。");
        console.error(error);
      }
    }
  }
};
</script>
<style scoped>
.login {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
}
.login-wrap {
  width: 40%;
  background-color: white;
  border-radius: 5px;
  margin-top: 80px;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
}
.login-ttl {
  width: calc(100%-15px);
  height: 35px;
  color: white;
  padding-top: 18px;
  padding-left: 15px;
  margin-bottom: 35px;
  border-radius: 5px 5px 0 0;
  background-color: rgba(30, 119, 253, 1);
}
.login-item {
  display: flex;
  justify-content: center;
  margin: 25px 0;
}
.login-item img {
  margin-right: 10px;
}
.login-item input {
  width: 75%;
  border: none;
  border-bottom: 3px solid rgb(169, 168, 168);
  font-size: large;
}
.error {
  text-align: center;
  margin: 10px 0;
  color: red;
  font-size: small;
}
.login-btn {
  margin-bottom: 35px;
  margin-left: 75%;
  width: 15%;
  height: 40px;
  font-size: large;
}

.mail-login-link {
  margin-top: 40px;
  width: 100%;
  text-align: center;
}

@media screen and (max-width:768px) {
  .login-wrap {
    width: 80%;
    margin: 0 auto;
  }
}
</style>