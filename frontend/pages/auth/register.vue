<template>
  <div class="register">
    <div class="register-wrap">
      <p class="register-ttl">Registration</p>
      <validation-observer ref="obs" v-slot="ObserverProps">
        <!-- ユーザーネーム -->
        <validation-provider v-slot="ProviderProps" rules="required|max:191">
          <div class="register-item">
            <img src="~/assets/img/user.png" alt="ユーザー">
            <input type="text" name="ユーザーネーム" v-model="name" placeholder="name" />
          </div>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <!-- メールアドレス -->
        <validation-provider v-slot="ProviderProps" rules="required|max:191|email">
          <div class="register-item">
            <img src="~/assets/img/mail.png" alt="メールアドレス">
            <input type="email" name="メールアドレス" v-model="email" placeholder="email" />
          </div>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <!-- パスワード -->
        <validation-provider v-slot="ProviderProps" rules="required|min:8|max:191">
          <div class="register-item">
            <img src="~/assets/img/password.png" alt="パスワード">
            <input type="password" name="パスワード" v-model="password" placeholder="password" />
          </div>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <button @click="register" :disabled="ObserverProps.invalid || !ObserverProps.validated"
          class="register-btn btn">登録</button>
      </validation-observer>
    </div>
  </div>
</template>

<script>
import { getAuth, createUserWithEmailAndPassword } from '@firebase/auth';
export default {
  data() {
    return {
      name: null,
      email: null,
      password: null,
    };
  },
  methods: {
    async register() {
      const auth = getAuth();
      let userCredential;
      try {
        userCredential = await createUserWithEmailAndPassword(auth, this.email, this.password);
      } catch (error) {
        console.error(error);
        alert('このメールアドレスは使用できません')
        return;
      }
      const user = userCredential.user;
      const sendData = {
        id: user.uid,
        name: this.name,
        email: this.email,
      }
      try {
        const { data } = await this.$axios.post("/api/user", sendData);
        if (data) {
          this.$router.replace("/auth/thanks");
        } else {
          alert('新規登録に失敗しました')
        }
      } catch (error) {
        console.error(error);
        alert('新規登録に失敗しました')
        return;
      }
    }
  }
}
</script>
<style scoped>
.register {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
}

.register-wrap {
  width: 40%;
  background-color: white;
  border-radius: 5px;
  margin-top: 80px;
  box-shadow: 5px 5px 5px 0 rgba(0, 0, 0, .5);
}

.register-ttl {
  width: calc(100%-15px);
  height: 35px;
  color: white;
  padding-top: 18px;
  padding-left: 15px;
  margin-bottom: 35px;
  border-radius: 5px 5px 0 0;
  background-color: rgba(30, 119, 253, 1);
}

.register-item {
  display: flex;
  justify-content: center;
  margin: 25px 0;
}

.register-item img {
  margin-right: 10px;
}

.register-item input {
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

.register-btn {
  margin-bottom: 35px;
  margin-left: 75%;
  width: 15%;
  height: 40px;
  font-size: large;
}

@media screen and (max-width:768px) {
  .register-wrap {
    width: 80%;
    margin: 0 auto;
  }

}
</style>