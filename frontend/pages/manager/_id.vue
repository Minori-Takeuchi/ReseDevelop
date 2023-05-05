<template>
  <div>
    <h2 class="ttl">店舗代表者用</h2>
    <p class="username">{{ $store.state.user.name }}さん</p>
    <div class="shop-create">
      <p class="manager-ttl">店舗を作成する</p>
      <validation-observer ref="obs" v-slot="ObserverProps">
        <validation-provider v-slot="ProviderProps" rules="required">
          <input type="text" name="店舗名" v-model="form.shop_name" placeholder="店舗名" class="manager-form">
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <validation-provider v-slot="ProviderProps" rules="required">
          <select name="エリア" v-model="form.area_id" class="manager-form">
            <option disabled selected value>選択してください</option>
            <option v-for="area in areas" :key="area.id" :value="area.id">{{ area.area }}</option>
          </select>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <validation-provider v-slot="ProviderProps" rules="required">
          <select name="ジャンル" v-model="form.genre_id"  class="manager-form">
            <option disabled selected value>選択してください</option>
            <option v-for="genre in genres" :key="genre.id" :value="genre.id">{{ genre.genre }}</option>
          </select>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <validation-provider v-slot="ProviderProps" rules="max:300">
          <textarea name="店舗説明" v-model="form.content" placeholder="店舗説明（任意:300字以内）" maxlength="300" class="manager-form__textarea"></textarea>
          <div class="error">{{ ProviderProps.errors[0] }}</div>
        </validation-provider>
        <div class="file-upload flex">
          <label for="file">画像をアップロード</label>
          <input @change="selectedFile" name="店舗画像" type="file" id="file">
          <span class="file-name">{{ form.img.name }}</span>
        </div>
        <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
        <button @click="insertShop" :disabled="ObserverProps.invalid || !ObserverProps.validated"  class="manager-btn">作成</button>
      </validation-observer>
    </div>
    <p class="manager-ttl">店舗一覧</p>
    <table class="manager-table">
      <tr>
        <th>店舗ID</th>
        <th>店舗名</th>
        <th>店舗予約一覧へ</th>
      </tr>
      <tr v-for="shop in shops" :key="shop.id">
        <td>{{ shop.id }}</td>
        <td>{{ shop.shop_name }}</td>
        <td>
          <button class="reservation-btn" @click="openReservations(shop)">予約一覧へ</button>
        </td>
      </tr>
    </table>
    <ModalMenu v-if="selectShop">
      <p class="manager-ttl">{{ selectShop.shop_name }} の予約一覧</p>
      <table class="manager-table">
        <tr>
          <th>予約ID</th>
          <th>予約日時</th>
          <th>予約人数</th>
          <th>コース</th>
        </tr>
        <tr  v-for="reservation in selectShop.reservations" :key="reservation.id">
          <td>{{ reservation.id }}</td>
          <td>{{ reservation.date }}{{ reservation.time }}</td>
          <td>{{ reservation.user_name }}</td>
          <td>{{ reservation.course }}</td>
        </tr>
      </table>
      <button @click="closeReservations" class="reservation-btn close-btn">店舗一覧へ</button>
    </ModalMenu>
  </div>
</template>
<script>
export default {
  data() {
    return {
      form: {
        shop_name: '',
        area_id:'',
        genre_id: '',
        img: '',
        content: '',
      },
      shops: [],
      areas: [],
      genres: [],
      selectShop:'',
      errorMessage:'',
    }
  },
  methods: {
    // 代表者の店舗データを取得
    async getShopsData() {
      if (this.$store.state.user.role === 2) {
        const { data } = await this.$axios.get("/api/manager/" + this.$store.state.user.uid)
        this.shops = data.shops
        this.areas = data.areas
        this.genres = data.genres
      }
    },
    // アップロード画像データを取得
    selectedFile(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.onloadend = () => {
        const fileType = reader.result.slice(0, 5);
        if (fileType !== 'data:') {
          this.errorMessage = 'ファイル形式が正しくありません。';
        } else if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
          this.errorMessage = 'JPEG、PNG、GIF 形式のファイルをアップロードしてください。';
        } else {
          this.form.img = file;
          this.errorMessage = '';
        }
      };
      reader.readAsDataURL(file);
    },
    // 店舗データ作成
    async insertShop() {
      try {
        const formData = new FormData();
        formData.append('shop_name', this.form.shop_name);
        formData.append('area_id', this.form.area_id);
        formData.append('genre_id', this.form.genre_id);
        if (this.form.content !== '') {
          formData.append('content', this.form.content);
        }
        if (this.form.img !== '') {
          formData.append('img', this.form.img, this.form.img.name);
        }
        formData.append('manager_id', this.$store.state.user.uid);
        for (const [key, value] of formData.entries()) {
          console.log(key + ": " + value);
        }
        const confirmed = window.confirm('店舗を作成しますか？')
        if (confirmed) {
          const { data } = await this.$axios.post("/api/manager/", formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          const shopData = data.shop
          console.log(shopData)
          this.shops.push(shopData)
          alert("店舗データを作成しました");
        } else {
          return false
        }
      } catch(error) {
        alert("作成に失敗しました");
        console.log(error);
      }
    },
    openReservations(shop) {
      this.selectShop = shop
    },
    closeReservations() {
      this.selectShop = null
    },
  },
  async created() {
    this.getShopsData()
  }
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
.shop-create {
  width: 80%;
  height: 100%;
  margin: 20px auto 40px auto;
  padding: 20px;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  background-color: rgb(217, 217, 217);
  border-radius: 5px;
}
.manager-ttl {
  width: 100%;
  text-align: center;
  font-weight: bold;
  margin-bottom: 20px;
}
.manager-form {
  width: 400px;
  font-size: large;
  margin: 10px 0;
  border-radius: 5px;
  border: none;
  height: 30px;
  background-color: white;
}
.manager-form__textarea {
  width: 400px;
  font-size: large;
  margin: 10px 0;
  border-radius: 5px;
  border: none;
  height: 80px;
  background-color: white;
}
.manager-btn {
  height: 30px;
  width: 100px;
  margin: 20px auto;
  display: block;
}
.manager-table {
  width: 80%;
  margin: 0 auto 40px auto;
  border-bottom: 3px solid black;
}
.manager-table tr th,
.manager-table tr td {
  vertical-align:middle;
  text-align: center;
  height: 35px;
}
.manager-table > tr:not(:last-child) {
  border-bottom: 1px solid black;
}
.manager-table tr td table tr {
  height: 24px;
}
.manager-table tr th {
  border-bottom: 3px solid black;
}
.file-upload input[type="file"] {
  display: none;
}
.file-name {
  margin: 30px 0 0 10px;
}
.file-upload label {
  height: 30px;
  width: 200px;
  background-color: rgba(30, 119, 253, 1);
  border-radius: 5px;
  color: white;
  cursor: pointer;
  text-align: center;
  line-height: 30px;
  margin: 20px 0;
  display: block;
}
.reservation-btn {
  background-color: rgba(30, 119, 253, 1);
  border-radius: 5px;
  color: white;
  cursor: pointer;
  height: 28px;
  border: none;
}
.close-btn {
  display: block;
  margin: 0 auto;
}
.error {
  color: red;
}
</style>