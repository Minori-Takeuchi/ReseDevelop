<template>
  <div class="login">
    <div class="login-wrap">
      <p class="login-ttl">Mail Login</p>
      <validation-observer ref="obs" v-slot="ObserverProps">
        <validation-provider v-slot="ProviderProps" rules="required|max:191|email">
          <div class="login-item">
            <img src="~/assets/img/mail.png" alt="メールアドレス">
            <input type="email" name="メールアドレス" v-model="email" placeholder="email" />
          </div>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <button @click="mailLogin" :disabled="ObserverProps.invalid || !ObserverProps.validated" class="login-btn btn">送信</button>
      </validation-observer>
    </div>
  </div>
</template>
<script>
import {  getAuth, sendSignInLinkToEmail } from "firebase/auth";
export default {
  data() {
    return {
      email: '',
    };
  },
  methods: {
    async mailLogin() {
      if (!this.email) {
        alert("メールアドレスが入力されていません。");
        return;
      }
      const auth = getAuth();
      const actionCodeSettings = {
        url: this.$config.appUrl,
        handleCodeInApp: true
      }
      await sendSignInLinkToEmail(auth, this.email, actionCodeSettings)
        .then(() => {
          alert('認証メールを送信しました')
          window.localStorage.setItem('emailForSignIn', this.email);
          this.$router.push("/")
        })
        .catch((error) => {
          console.log(error)
          alert('メールの送信に失敗しました')
        })
    },
  }
}
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

@media screen and (max-width:768px) {
  .login-wrap {
    width: 80%;
    margin: 0 auto;
  }
}
</style>