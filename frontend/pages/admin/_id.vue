<template>
  <div class="container">
    <h2 class="ttl">管理画面</h2>
    <p class="username">{{ $store.state.user.name }}さん</p>
    <div>
      <p class="admin-ttl">店舗代表者一覧</p>
      <table class="admin-table">
        <tr>
          <th>店舗代表者</th>
          <th>メールアドレス</th>
          <th>担当店舗</th>
        </tr>
        <tr v-for="manager in managers" :key="manager.id">
          <td>{{ manager.name }}</td>
          <td>{{ manager.email }}</td>
          <td>
            <table>
              <tr v-for="shop in manager.shops" :key="shop.id">
                <td>{{ shop.shop_name }}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
    <div>
      <p class="admin-ttl">利用者一覧（お気に入り登録を促すメールを送信できます）</p>
      <table class="admin-table">
        <tr>
          <th>利用者</th>
          <th>メールアドレス</th>
          <th>お気に入り店舗数</th>
          <th>メール送信</th>
        </tr>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.likes.length }}</td>
          <td><button @click="sendMail(user.email)" class="btn admin-btn">メールを送る</button></td>
        </tr>
      </table>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      shops: [],
      managers:[],
      users:[]
    }
  },
  methods: {
    async getShopData() {
      const { data } = await this.$axios.get("/api/admin/");
      this.shops = data.shops
      this.managers = data.managers
      this.users = data.users
    },
    async sendMail(email) {
      try {
        const { data } = await this.$axios.post("/api/mail/", { email })
        alert('メールを送信しました')
        console.log(data.message)
      } catch (error) {
        alert('メールの送信に失敗しました')
        console.log(error)
      }
    }
  },
  async created() {
      await this.getShopData();
  },
}
</script>
<style scoped>
.ttl {
  display: flex;
  font-size: 24px;
  font-weight: bold;
  margin: 20px 0;
}
.username {
  display: flex;
  justify-content: center;
  font-size: 20px;
  font-weight: bold;
  margin: 20px 0;
}
.admin-ttl {
  width: 100%;
  text-align: center;
  font-weight: bold;
  margin-bottom: 20px;
}
.admin-btn {
  height: 30px;
  width: 100px;
  margin: 20px auto;
  display: block;
}
.admin-table {
  width: 80%;
  margin: 0 auto 40px auto;
  border-bottom: 3px solid black;
}
.admin-table tr th,
.admin-table tr td, 
.admin-table tr td table tr td {
  vertical-align:middle;
  text-align: center;
}
.admin-table > tr:not(:last-child) {
  border-bottom: 1px solid black;
}
.admin-table tr td table tr {
  height: 24px;
}
.admin-table tr td table tr:not(:last-child) {
  border-bottom: 1px dashed black;
}
.admin-table tr td table {
  width: 100%;
}
.admin-table tr th {
  border-bottom: 3px solid black;
}
</style>